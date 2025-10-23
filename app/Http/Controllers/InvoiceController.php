<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Smalot\PdfParser\Parser;



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

    public function store(Request $request)
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

        if ($file->getClientOriginalExtension() === 'pdf') {
            $parser = new Parser();
            $pdf = $parser->parseFile($originalPath);
            $text = $pdf->getText();
        } else {
            $ocr = (new TesseractOCR($originalPath))
                ->executable(config('services.tesseract.path'))
                ->lang(config('services.tesseract.langs'));
            $text = $ocr->run();
        }

        $allData = $this->parseAll($text);
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

        $normalize = fn($v) => isset($v) ? floatval(str_replace([' ', ','], ['', '.'], $v)) : null;
        $data['montant_ht'] = $normalize($data['montant_ht'] ?? null);
        $data['montant_tva'] = $normalize($data['montant_tva'] ?? null);
        $data['montant_ttc'] = $normalize($data['montant_ttc'] ?? null);

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


    /**
     * Extract numero_commande (invoice number)
     */
    public function extractOrderNumber($text)
    {
        if (preg_match('/No\s+commande\s+.*?\n\s*(\d+)/is', $text, $matches)) {
            return $matches[1];
        }
        if (preg_match('/N°\s+de\s+Commande\s*:\s*(\d+)/i', $text, $matches)) {
            return $matches[1];
        }


        return null;
    }

    /**
     * Extract date_commande (invoice date)
     */
    public function extractOrderDate($text)
    {
        // atkadaw
        // Pattern 1: Standard format with newline
        if (preg_match('/Date\s+commande\s+.*?\n\s*(\d{2}\/\d{2}\/\d{2})/is', $text, $matches) || preg_match('/Date\s+commande.*?\|\s*(\d{2}\/\d{2}\/\d{2})/is', $text, $matches)) {
            $rawDate = $matches[1];
            [$day, $month, $year] = explode('/', $rawDate);

            // Add 20 in front of the 2-digit year
            $formattedDate = sprintf('20%s-%s-%s', $year, $month, $day);

            return $formattedDate;
        }
        if (preg_match('/Date\s+de\s+commande\s+.*?\n\s*(\d{2}\/\d{2}\/\d{2})/is', $text, $matches)) {
            $rawDate = $matches[1];
            [$day, $month, $year] = explode('/', $rawDate);

            // Add 20 in front of the 2-digit year
            $formattedDate = sprintf('20%s-%s-%s', $year, $month, $day);

            return $formattedDate;
        }
        // kazyon
        if (preg_match('/Date\s+Commande\s*:\s*(\d{2})[\/\.](\d{2})[\/\.](\d{2,4})/i', $text, $matches)) {
            $day = $matches[1];
            $month = $matches[2];
            $year = $matches[3];

            // If the year is 2 digits, prefix it with "20"
            if (strlen($year) === 2) {
                $year = '20' . $year;
            }

            // Format as YYYY-MM-DD
            return sprintf('%s-%s-%s', $year, $month, $day);
        }

        return null;
    }

    /**
     * Extract montant_ht (amount before tax)
     */
    public function extractMontantHt($text)
    {
        if (preg_match('/(?:TOTAL|Montant)\s+HT\s*[:\s]*([\d\s]+[.,]\d+)/i', $text, $matches)) {
            return $this->normalizeAmount($matches[1]);
        }

        return null;
    }

    /**
     * Extract montant_tva (VAT amount)
     */
    public function extractMontantTva($text)
    {
        // Alternative: Look for "Montant" after percentage
        if (preg_match('/\d+\.\d+\s*%\s+Assiette[^\d]+[\d\s.,]+\s+Montant\s+([\d\s.,]+)/is', $text, $matches)) {
            return $this->normalizeAmount($matches[1]);
        }
        // Fix: Look for the correct pattern without extra zeros
        if (preg_match('/MONTANT\s+TVA\s+TOTAL\s+(\d+)/i', $text, $matches)) {
            $amount = trim($matches[1]);
            // Remove the trailing '+' or other characters
            $amount = rtrim($amount, '+');
            return $this->normalizeAmount($amount);
        }

        return null;
    }

    /**
     * Extract montant_ttc (total amount including tax)
     */
    public function extractMontantTtc($text)
    {
        if (preg_match('/(?:Total|MONTANT)\s+(?:T\.?T\.?C\.? )\s*[:\s]*([\d\s]+[.,]\d+)/i', $text, $matches)) {
            return $this->normalizeAmount($matches[1]);
        }
        if (preg_match('/Total\s*T\.?T\.?C\.?\s*[\t:]*\s*([\d\s]+[.,]?\d*)/i', $text, $matches)) {
            return $this->normalizeAmount($matches[1]);
        }
        if (preg_match('/Total\s+Unités\s*\\t\s*([\d.,]+)/i', $text, $matches)) {
            return $this->normalizeAmount($matches[1]);
        }
        if (preg_match('/Quantite\s+totale\s*\n.*?\t([\d.,]+)/i', $text, $matches)) {
            return $this->normalizeAmount($matches[1]);
        }



        return null;
    }

    /**
     * Normalize amount (remove spaces, convert comma to dot)
     */
    private function normalizeAmount($amount)
    {
        $amount = str_replace(' ', '', $amount);
        $amount = str_replace(',', '.', $amount);
        return floatval($amount);
    }


    /**
     * Extract nom_fournisseur (supplier name)
     */
    public function extractNomFournisseur($text)
    {
        if (preg_match('/Commande\s+a[^\n\r]*[\r\n]+.*?\b([A-Z]{3,})\b\s*$/m', $text, $m)) {
            return trim($m[1]);
        }
        if (preg_match('/Nom\s+Fournisseur\s*:\s*(\w+)/i', $text, $m)) {
            return trim($m[1]);
        }
        if (preg_match('/Commande par.*?\n.*?\t.*?\t([^\n]+)/i', $text, $matches)) {
            return trim($matches[1]);
        }

        return 'Nom introuvable';
    }

    /**
     * Extract code_fournisseur (supplier code)
     */
    public function extractCodeFournisseur($text)
    {
        // Look for the number after the date and before contract code
        if (preg_match('/\d{2}\/\d{2}\/\d{2}\s+\d{2}:\d{2}\s+(\d{2,4})\s+/is', $text, $matches)) {
            return trim($matches[1]);
        }
        if (preg_match('/Code\s+Fournisseur\s*:\s*(\d+)/i', $text, $m)) {
            return trim($m[1]);
        }

        return null;
    }

    /**
     * Extract commande_par (ordered by)
     */
    public function extractCommandePar($text)
    {
        // 1️⃣ Try to extract using "Nom Société : ..."
        if (preg_match('/Nom\s+Société\s*:\s*(.+)/i', $text, $m)) {
            return trim(preg_replace('/\s+/', ' ', $m[1]));
        }

        $lines = explode("\n", $text);
        $commandePar = [];
        $inTable = false;

        foreach ($lines as $line) {
            $line = trim($line);

            // Detect start of the table
            if (stripos($line, 'Commande par') !== false && stripos($line, 'Commande a') !== false) {
                $inTable = true;
                continue;
            }

            // Stop when table ends
            if ($inTable) {
                if ($line === '' || stripos($line, 'Article') !== false) {
                    break;
                }

                // Skip I.C.E lines inside the table
                if (stripos($line, 'I.C.E') !== false) {
                    continue;
                }

                // Extract first column (Commande par)
                $columns = preg_split('/\s{2,}|\t/', $line);
                if (count($columns) >= 1 && !empty($columns[0])) {
                    $commandePar[] = rtrim(trim($columns[0]), ',');
                }
            }
        }

        return implode(', ', array_filter($commandePar));
    }


    /**
     * Extract commande_a (deliver to)
     */
    public function extractCommandeA($text)
    {
        // 1️⃣ Try to extract using "Adr. de livraison : ... Adr. de facturation"
        if (preg_match('/Adr\.?\s+de\s+livraison\s*:\s*(.*?)Adr\.?\s+de\s+facturation/is', $text, $m)) {
            return trim(preg_replace('/\s+/', ' ', $m[1]));
        }

        $lines = explode("\n", $text);
        $commandeA = [];
        $inTable = false;

        foreach ($lines as $line) {
            $line = trim($line);

            if (stripos($line, 'Commande par') !== false && stripos($line, 'Commande a') !== false) {
                $inTable = true;
                continue;
            }

            if ($inTable) {
                if ($line === '' || stripos($line, 'Article') !== false) {
                    break;
                }

                $columns = preg_split('/\s{2,}|\t/', $line);
                if (count($columns) >= 3 && !empty($columns[2])) {
                    $commandeA[] = rtrim(trim($columns[2]), ',');
                }
            }
        }

        return implode(', ', array_filter($commandeA));
    }


    /**
     * Parse all data from PDF text
     */
    public function parseAll($text)
    {
        return [
            'reference_commande' => $this->extractOrderNumber($text),
            'date_commande' => $this->extractOrderDate($text),
            'nom_fournisseur' => $this->extractNomFournisseur($text),
            'code_fournisseur' => $this->extractCodeFournisseur($text),
            'commande_par' => $this->extractCommandePar($text),
            'commande_a' => $this->extractCommandeA($text),
            'montant_ht' => $this->extractMontantHt($text),
            'montant_tva' => $this->extractMontantTva($text),
            'montant_ttc' => $this->extractMontantTtc($text),
        ];
    }
}
