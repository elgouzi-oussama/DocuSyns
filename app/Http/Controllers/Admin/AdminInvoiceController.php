<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\InvoiceExtractorService;
use App\Services\InvoiceParserForArticles;
use App\Services\InvoiceParserService;
use Smalot\PdfParser\Parser;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AdminInvoiceController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
            new Middleware('permission:invoice.index', only: ['index']),
            new Middleware('permission:invoice.show', only: ['show']),
            new Middleware('permission:invoice.create', only: ['create', 'store', 'confirm']),
            new Middleware('permission:invoice.edit', only: ['edit', 'update']),
            new Middleware('permission:invoice.delete', only: ['destroy']),
            new Middleware('permission:invoice.approve', only: ['approve']),
            new Middleware('permission:invoice.reject', only: ['reject']),
        ];
    }

    /**
     * Display a listing of all invoices (admin only)
     */

    public function index()
    {
        $invoices = Invoice::latest()->paginate(10);


        return view('admin.invoices.index', compact('invoices'));
    }
    /**
     * Display the specified invoice
     */
    public function show($id)
    {
        $invoice = Invoice::with('user')->findOrFail($id);
        return view('admin.invoices.show', compact('invoice'));
    }


    /**
     * Show the form for creating a new invoice
     */
    public function create()
    {
        $users = User::where('role', 'user')->get();
        return view('admin.invoices.create', compact('users'));
    }

    /**
     * Store a newly created invoice in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'file' => 'required|file|mimes:jpg,jpeg,png,bmp,tiff,pdf|max:5120',
            'statut' => 'required|in:en_attente,approuvé,rejeté',
        ], [
            'user_id.required' => __('admin.invoice.validation.user_required'),
            'user_id.exists' => __('admin.invoice.validation.user_exists'),
            'file.required' => __('admin.invoice.validation.file_required'),
            'file.file' => __('admin.invoice.validation.file_type'),
            'file.mimes' => __('admin.invoice.validation.file_mimes'),
            'file.max' => __('admin.invoice.validation.file_max'),
            'statut.required' => __('admin.invoice.validation.status_required'),
            'statut.in' => __('admin.invoice.validation.status_invalid'),
        ]);

        // Store the uploaded file
        $file = $request->file('file');
        $relativePath = $file->store('invoice', 'public');
        $originalPath = storage_path('app/public/' . $relativePath);

        // Handle PDF conversion to image
        if ($file->getClientOriginalExtension() === 'pdf') {
            $parser = new Parser();
            $pdf = $parser->parseFile($originalPath);
            $text = $pdf->getText();
        } else {
            // orc 
            $ocr = (new TesseractOCR($originalPath))
                ->executable(config('services.tesseract.path'))
                ->lang(config('services.tesseract.langs'));

            $text = $ocr->run();
        }
        // dd($text);
        // $parserai = new InvoiceExtractorService();
        // $data = $parserai->extractFields($text);
        // dd($data);

        $lines = explode("\n", $text);
        dd($lines);
        $parserai = new InvoiceParserForArticles();
        $articles = $parserai->parse($lines);
        dd($articles);



        // $articles = $this->extractArticles($text);
        // dd($articles);


        // Extract all data at once
        $parser = new InvoiceParserService();
        $allData = $parser->parseAll($text);
        $allData['file'] = $relativePath;
        $allData['user_id'] = $validated['user_id'];
        $allData['statut'] = $validated['statut'];
        $allData['items'] = $articles;
        dd($allData);
        if (Invoice::where('reference_commande', $allData['reference_commande'])->exists()) {
            return  redirect()->route(userRoute('invoice.create'))->with('error', __('admin.invoice.reference_exists'));
        }


        return view('admin.invoices.see', compact('allData'));
    }

    /**
     * Confirm a newly created invoice in storage
     */
    public function confirm(Request $request)
    {
        $data = $request->except('_token');
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
            return redirect()->route(userRoute('invoices.create'))
                ->with('error', __('admin.invoice.reference_exists'));
        }

        $invoice = Invoice::create($data);
        $invoice = $invoice['id'];

        return redirect()->route(userRoute('invoices.show'), compact('invoice'))
            ->with('success', __('admin.invoice.created_success'));
    }


    /**
     * Show the form for editing the specified invoice
     */
    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        $users = User::where('role', 'user')->get();

        return view('admin.invoices.edit', compact('invoice', 'users'));
    }

    /**
     * Update the specified invoice
     */
    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        // Optional: validate fields
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
            'statut'             => 'required|in:en_attente,approuvé,rejeté',
            'user_id'            => 'required|exists:users,id',
        ], [
            'reference_commande.required' => __('admin.invoice.validation.reference_commande_required'),
            'date_commande.required'      => __('admin.invoice.validation.date_commande_required'),
            'nom_fournisseur.required'    => __('admin.invoice.validation.nom_fournisseur_required'),
            'commande_par.required'       => __('admin.invoice.validation.commande_par_required'),
            'commande_a.required'         => __('admin.invoice.validation.commande_a_required'),
            'montant_ht.required'         => __('admin.invoice.validation.montant_ht_required'),
            'montant_tva.required'        => __('admin.invoice.validation.montant_tva_required'),
            'montant_ttc.required'        => __('admin.invoice.validation.montant_ttc_required'),
            'statut.required'             => __('admin.invoice.validation.statut_required'),
            'user_id.required'            => __('admin.invoice.validation.user_id_required'),
            'user_id.exists'              => __('admin.invoice.validation.user_id_exists'),
        ]);



        // Convert amounts like "810 330,00" → "810330.00"
        $normalize = function ($value) {
            return floatval(str_replace([' ', ','], ['', '.'], $value));
        };

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
            'user_id'            => $request->user_id,
            'statut'             => $request->statut,
        ]);




        return redirect()->route(userRoute('invoices.show'), $invoice->id)
            ->with('success', __('admin.invoice.updated_success'));
    }

    /**
     * Remove the specified invoice from storage
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return redirect()->route(userRoute('invoices.index'))
            ->with('success', __('admin.invoice.deleted_success'));
    }

    /**
     * Approve an invoice
     */
    public function approve($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update(['statut' => 'approuvé']);

        return redirect()->back()
            ->with('success', __('admin.invoice.approved_success'));
    }

    /**
     * Reject an invoice
     */
    public function reject($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update(['statut' => 'rejeté']);

        return redirect()->back()
            ->with('success', __('admin.invoice.rejected_success'));
    }


































    public function extractArticles(string $text): array
    {
        // dd($text);
        $lines = explode("\n", $text);
        $articles = [];
        dd($lines);
        foreach ($lines as $line) {
            // Match lines that start with an article number (digits)
            if (preg_match(
                '/^(\d+)\s+([A-Za-z0-9\s\-\(\)\/]+?)\s+Piece\s+(\d+)\s+(\d+)\s+(\d+)\s+([\d.]+)\s+([\d.]+)\s+Piece\s+([\d\s.]+)/',
                $line,
                $m
            )) {
                var_dump($m);
                $articles[] = [
                    'Article' => $m[1],
                    'Libelle article' => trim($m[2]),
                    'Type U.C.' => 'Piece',
                    'VL' => '0',
                    'No ligne' => $m[3],
                    'UVC/UC' => '1',
                    'Quant en UC' => $m[4],
                    'Prix achat net' => $m[5],
                    'Unite' => 'Piece',
                    'Total prix achat net' => $m[6],
                    'No. operation speciale' => ''
                ];
            }
        }

        return $articles;
    }
}
