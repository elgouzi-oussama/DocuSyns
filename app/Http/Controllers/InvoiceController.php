<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Services\InvoiceExtractorService;
use App\Services\InvoiceParserForArticles;
use App\Services\InvoiceParserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Smalot\PdfParser\Parser;
use Imagick;



class InvoiceController extends Controller
{

    public function index()
    {
        $invoices = Invoice::latest()->where('user_id', Auth::id())->distinct()->get();
        return view('user.invoice.index', compact('invoices'));
    }

    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);
        return view('user.invoice.show', compact('invoice'));
    }

    public function create()
    {
        return view('user.invoice.create');
    }

    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        return view('user.invoice.edit', compact('invoice'));
    }

    // 🟩 Update — Save the modified data
    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        $request->validate([
            'reference_commande' => 'required|string|max:100',
            'date_commande'      => 'required|date',
            'nom_fournisseur'    => 'required|string|max:191',
            'code_fournisseur'   => 'nullable|string|max:100',
            'commande_par'       => 'required|string|max:255',
            'commande_a'         => 'required|string|max:255',
            'montant_ht'         => 'required|string',
            'montant_tva'        => 'required|string',
            'montant_ttc'        => 'required|string',
        ], [
            'reference_commande.required' => __('invoice.validation.reference_commande_required'),
            'date_commande.required'      => __('invoice.validation.date_commande_required'),
            'nom_fournisseur.required'    => __('invoice.validation.nom_fournisseur_required'),
        ]);

        $normalize = fn($v) => floatval(str_replace([' ', ','], ['', '.'], $v));

        $invoice->update([
            'reference_commande' => $request->reference_commande,
            'date_commande'      => $request->date_commande,
            'nom_fournisseur'    => $request->nom_fournisseur,
            'code_fournisseur'   => $request->code_fournisseur,
            'commande_par'       => $request->commande_par,
            'commande_a'         => $request->commande_a,
            'montant_ht'         => $normalize($request->montant_ht),
            'montant_tva'        => $normalize($request->montant_tva),
            'montant_ttc'        => $normalize($request->montant_ttc),
            'user_id'            => Auth::id(),
        ]);

        return redirect()
            ->route('invoice.index')
            ->with('success', __('invoice.messages.updated'));
    }

    public function store(Request $request, InvoiceParserService $extractor, InvoiceExtractorService $extractors)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,bmp,tiff,pdf|max:5120'
        ], [
            'file.required' => __('invoice.validation.file_required'),
            'file.mimes'    => __('invoice.validation.file_mimes'),
            'file.max'      => __('invoice.validation.file_max'),
        ]);

        $file = $request->file('file');
        $relativePath = $file->store('invoice', 'public');
        $originalPath = storage_path('app/public/' . $relativePath);

        // Handle PDF conversion to image
        if ($file->getClientOriginalExtension() === 'pdf') {
            $parser = new Parser();
            $pdf = $parser->parseFile($originalPath);
            $text = $pdf->getText();
            // dd($text);
        } else {
            // orc 
            $ocr = (new TesseractOCR($originalPath))
                ->executable(config('services.tesseract.path'))
                ->lang(config('services.tesseract.langs'));

            $text = $ocr->run();
        }

        // $file = $request->file('file');
        // $extension = strtolower($file->getClientOriginalExtension());
        // $relativePath = null;
        // $text = '';
        // $allData = null;

        // if ($extension === 'pdf') {
        //     try {
        //         $parser = new Parser();
        //         $pdf = $parser->parseFile($file);
        //         $text = $pdf->getText();
        //         $allData = $extractor->parseAll($text);
        //         // ✅ Convert first page of PDF to image
        //         $imagick = new Imagick();
        //         $imagick->setResolution(300, 300);
        //         $imagick->readImage($file->getPathname() . '[0]'); // first page only
        //         $imagick->setImageFormat('jpg');

        //         // Generate unique filename
        //         $imageName = 'invoice/' . uniqid('invoice_') . '.jpg';

        //         // Get the full path for storage
        //         $fullPath = Storage::disk('public')->path($imageName);

        //         // Ensure the directory exists
        //         $directory = dirname($fullPath);
        //         if (!file_exists($directory)) {
        //             mkdir($directory, 0755, true);
        //         }

        //         // Write the image to disk
        //         $imagick->writeImage($fullPath);
        //         $imagick->clear();
        //         $imagick->destroy();

        //         // Set relative path for database
        //         $relativePath = $imageName;

        //         // OCR from the generated image
        //         $text = (new TesseractOCR($fullPath))
        //             ->executable(config('services.tesseract.path'))
        //             ->lang(config('services.tesseract.langs'))
        //             ->run();
        //     } catch (\Exception $e) {
        //         $text = 'Error converting PDF: ' . $e->getMessage();
        //     }
        // } else {
        //     // ✅ Image file → save & OCR
        //     $relativePath = $file->store('invoice', 'public');
        //     $fullPath = Storage::disk('public')->path($relativePath);

        //     try {
        //         $text = (new TesseractOCR($fullPath))
        //             ->executable(config('services.tesseract.path'))
        //             ->lang(config('services.tesseract.langs'))
        //             ->run();
        //     } catch (\Exception $e) {
        //         $text = 'Error running OCR: ' . $e->getMessage();
        //     }
        // }
        // $allData = $extractors->extractFields($text);
        if (empty($allData)) {
            $allData = $extractor->parseAll($text);
            if (empty($allData['montant_ttc']) && !empty($allData['montant_ht']) && empty($allData['montant_tva'])) {
                $allData['montant_ttc'] = $allData['montant_ht'];
            }
        }
        $lines = explode("\n", $text);
        $parserai = new InvoiceParserForArticles();
        $articles = $parserai->parse($lines);

        $allData['items'] = $articles;
        $allData['file'] = $relativePath;

        if (Invoice::where('reference_commande', $allData['reference_commande'])->exists()) {
            return redirect()->route('invoice.create')->with('error', __('invoice.errors.duplicate_reference'));
        }

        return view('user.invoice.see', compact('allData'));
    }

    public function confirm(Request $request)
    {
        $data = $request->except('_token');
        $data['user_id'] = Auth::id();
        $data['articles'] = json_decode($request->input('articles'), true);


        $normalize = fn($v) => isset($v) ? floatval(str_replace([' ', ','], ['', '.'], $v)) : null;
        if (isset($data['montant_ht'])) {
            $data['montant_ht']  = $normalize($data['montant_ht']);
        }

        if (isset($data['montant_tva'])) {
            $data['montant_tva'] = $normalize($data['montant_tva']);
        }

        if (isset($data['montant_ttc'])) {
            $data['montant_ttc'] = $normalize($data['montant_ttc']);
        }
        if (Invoice::where('reference_commande', $data['reference_commande'])->exists()) {
            return redirect()->route('invoice.create')->with('error', __('invoice.errors.duplicate_reference'));
        }

        $invoice = Invoice::create($data);
        $id = $invoice->id;

        $successMessage = __('invoice.messages.created');

        if (Gate::allows('invoice.show')) {
            return redirect()->route('invoice.show', compact('id'))->with('success', $successMessage);
        }

        return redirect()->route('invoice.index')->with('success', $successMessage);
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()
            ->route('invoice.index')
            ->with('success', __('invoice.messages.deleted'));
    }
}
