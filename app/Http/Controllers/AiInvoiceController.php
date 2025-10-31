<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Services\InvoiceExtractorService;
use Illuminate\Http\Request;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Support\Facades\Storage;
use Imagick;
use Smalot\PdfParser\Parser;

class AiInvoiceController extends Controller
{
    public function store(Request $request, InvoiceExtractorService $extractors)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,bmp,tiff,pdf|max:5120'
        ], [
            'file.required' => __('invoice.validation.file_required'),
            'file.mimes'    => __('invoice.validation.file_mimes'),
            'file.max'      => __('invoice.validation.file_max'),
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        $relativePath = null;
        $text = '';
        $allData = null;

        if ($extension === 'pdf') {
            try {
                $parser = new Parser();
                $pdf = $parser->parseFile($file);
                $text = $pdf->getText();
                $allData = $extractors->extractFields($text);
                $imagick = new Imagick();
                $imagick->setResolution(300, 300);
                $imagick->readImage($file->getPathname() . '[0]'); // first page only
                $imagick->setImageFormat('jpg');

                // Generate unique filename
                $imageName = 'invoice/' . uniqid('invoice_') . '.jpg';

                // Get the full path for storage
                $fullPath = Storage::disk('public')->path($imageName);

                // Ensure the directory exists
                $directory = dirname($fullPath);
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }

                // Write the image to disk
                $imagick->writeImage($fullPath);
                $imagick->clear();
                $imagick->destroy();

                // Set relative path for database
                $relativePath = $imageName;

                // OCR from the generated image
                $text = (new TesseractOCR($fullPath))
                    ->executable(config('services.tesseract.path'))
                    ->lang(config('services.tesseract.langs'))
                    ->run();
            } catch (\Exception $e) {
                $text = 'Error converting PDF: ' . $e->getMessage();
            }
        } else {
            // ✅ Image file → save & OCR
            $relativePath = $file->store('invoice', 'public');
            $fullPath = Storage::disk('public')->path($relativePath);

            try {
                $text = (new TesseractOCR($fullPath))
                    ->executable(config('services.tesseract.path'))
                    ->lang(config('services.tesseract.langs'))
                    ->run();
            } catch (\Exception $e) {
                $text = 'Error running OCR: ' . $e->getMessage();
            }
        }
        if (empty($allData)) {
            $allData = $extractors->extractFields($text);
        }
        if (empty($allData['montant_ttc']) && !empty($allData['montant_ht']) && empty($allData['montant_tva'])) {
            $allData['montant_ttc'] = $allData['montant_ht'];
        }
        if (isset($allData['date_commande'])) {
            $allData['date_commande'] = $this->normalizeDate($allData['date_commande']);
        }
        $allData['file'] = $relativePath;

        if (Invoice::where('reference_commande', $allData['reference_commande'])->exists()) {
            return redirect()->route('invoice.create')->with('error', __('invoice.errors.duplicate_reference'));
        }

        return view('user.invoice.see', compact('allData'));
    }














    private function normalizeDate(?string $date): ?string
    {
        if (empty($date)) {
            return null;
        }

        // Clean extra spaces or words
        $date = trim($date);

        // Match common formats: 22/09/25 or 22/09/2025
        if (preg_match('/^(\d{2})[\/\-](\d{2})[\/\-](\d{2,4})$/', $date, $m)) {
            $day = $m[1];
            $month = $m[2];
            $year = $m[3];

            // If 2-digit year, assume 2000+
            if (strlen($year) === 2) {
                $year = (int)$year + 2000;
            }

            return sprintf('%04d-%02d-%02d', $year, $month, $day);
        }

        // Try to parse using Carbon fallback
        try {
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
