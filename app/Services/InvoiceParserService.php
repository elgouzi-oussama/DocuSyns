<?php

namespace App\Services;

class InvoiceParserService
{

    // ----------------------------------------------------------
    // Main parser
    // ----------------------------------------------------------

    public function parseAll($text)
    {
        return ([
            'reference_commande' => $this->extractOrderNumber($text),
            'date_commande'      => $this->extractOrderDate($text),
            'nom_fournisseur'    => $this->extractNomFournisseur($text),
            'code_fournisseur'   => $this->extractCodeFournisseur($text),
            'commande_par'       => $this->extractCommandePar($text),
            'commande_a'         => $this->extractCommandeA($text),
            'montant_ht'         => $this->extractMontantHt($text),
            'montant_tva'        => $this->extractMontantTva($text),
            'montant_ttc'        => $this->extractMontantTtc($text),
        ]);
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
        // Normalize spaces and newlines
        $text = preg_replace('/\s+/', ' ', $text);

        // 🟢 Atacadao — date appears after article number (e.g., "2509002524841 22/09/25 00:00 319 CCFD")
        if (preg_match('/\b(\d{2})\/(\d{2})\/(\d{2})\b/', $text, $matches)) {
            $day = $matches[1];
            $month = $matches[2];
            $year = $matches[3];

            // Assume 20xx for 2-digit year
            if (strlen($year) === 2) {
                $year = '20' . $year;
            }

            return sprintf('%s-%s-%s', $year, $month, $day);
        }

        // 🟢 Standard "Date commande" line (Aswak, Atacadao formatted)
        if (preg_match('/Date\s+commande\s*[:\-]?\s*(\d{2})[\/\.](\d{2})[\/\.](\d{2,4})/i', $text, $matches)) {
            $day = $matches[1];
            $month = $matches[2];
            $year = $matches[3];

            if (strlen($year) === 2) {
                $year = '20' . $year;
            }

            return sprintf('%s-%s-%s', $year, $month, $day);
        }

        // 🟢 Kazyon
        if (preg_match('/Date\s+Commande\s*:\s*(\d{2})[\/\.](\d{2})[\/\.](\d{2,4})/i', $text, $matches)) {
            $day = $matches[1];
            $month = $matches[2];
            $year = $matches[3];

            if (strlen($year) === 2) {
                $year = '20' . $year;
            }

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
}
