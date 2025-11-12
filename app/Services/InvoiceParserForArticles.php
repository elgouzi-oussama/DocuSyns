<?php

namespace App\Services;

/**
 * InvoiceParser
 *
 * Parse a flat array of strings (like lines extracted from a PDF/CSV/whatever)
 * into an array of associative records representing articles.
 * 
 * Supports multiple invoice formats:
 * - Atacadao format (with prices)
 * - Aswak Essalam format (without prices)
 */
class InvoiceParserForArticles
{
    /**
     * Parse lines into structured article records.
     *
     * @param array $lines Flat array of strings (the raw data)
     * @return array List of associative arrays (records)
     */
    public static function parse(array $lines): array
    {
        // Normalize lines: replace tabs with spaces and trim
        $lines = array_map(function ($line) {
            return trim(preg_replace('/\t+/', ' ', $line));
        }, $lines);

        $n = count($lines);
        $rawRecords = [];
        $inArticleSection = false;

        // Build raw record strings by grouping lines starting with digits
        for ($i = 0; $i < $n; $i++) {
            $line = trim($lines[$i]);

            // skip empty lines
            if ($line === '') {
                continue;
            }

            // Detect article section header to start capturing articles
            if (preg_match('/^Article\s+Libelle\s+article/i', $line)) {
                $inArticleSection = true;
                continue;
            }

            // Stop capturing when we hit totals/summary section
            if (preg_match('/^TOTAL\s+HT|^TVA\s|^MONTANT|^Date\s+de\s+livraison/i', $line)) {
                $inArticleSection = false;
                continue;
            }

            // Only process article lines when in article section
            if (!$inArticleSection) {
                continue;
            }

            // Detect start of a record: line begins with digits (article code)
            // But exclude dates (contains /) and very long numbers (order numbers)
            if (preg_match('/^\d+/', $line) && !preg_match('/\//', $line)) {
                // Additional validation: check if line contains expected article structure
                $testLine = $line;
                $j = $i + 1;

                // Peek ahead to build complete record for validation
                while ($j < $n) {
                    $next = trim($lines[$j]);
                    if ($next === '') {
                        $j++;
                        continue;
                    }
                    // Stop at next article or summary lines
                    if (preg_match('/^\d+/', $next) && !preg_match('/\//', $next)) {
                        break;
                    }
                    if (preg_match('/^TOTAL\b|^TVA\b|^MONTANT\b|^Date\s+de\s+livraison/i', $next)) {
                        break;
                    }
                    $testLine .= ' ' . $next;
                    $j++;
                }

                // Validate that this looks like an article record
                if (preg_match('/\b(Piece|Pce|Pc|PCB|Unit|Unite|Kg|G)\b/i', $testLine)) {
                    $current = preg_replace('/\s+/', ' ', trim($testLine));
                    $rawRecords[] = $current;
                    $i = $j - 1;
                }
            }
        }

        $items = [];
        dd($rawRecords);
        foreach ($rawRecords as $raw) {
            $item = self::parseRecord($raw);
            if ($item) {
                $items[] = $item;
            }
        }

        return $items;
    }

    /**
     * Parse a single record string into an associative array
     */
    private static function parseRecord(string $raw): ?array
    {
        // Normalize spaces
        $raw = preg_replace('/\s+/', ' ', trim($raw));

        // Try Atacadao format first (with prices)
        $patternAtacadao = '/^
            (?P<Article>\d+)\s+
            (?P<Libelle>.+?)\s+
            (?P<Type>Piece|Pce|Pc|Unit|Unite|Kg|G)\s+
            (?P<VL>\d+)\s+
            (?P<No_ligne>\d+)\s+
            (?P<UVC_UC>\d+)\s+
            (?P<Quant_en_UC>[\d\.,\s]+)\s+
            (?P<Prix_achat_net>[\d\.,]+)\s*
            (?P<UniteField>Piece|Pce|Pc|Unit|Unite|Kg|G)?\s*
            (?P<Total_prix_achat_net>[\d\s\.,]+)
        $/ix';

        if (preg_match($patternAtacadao, $raw, $m)) {
            return [
                'Article' => $m['Article'],
                'Libelle article' => trim($m['Libelle']),
                'Type U.C.' => trim($m['Type']),
                'VL' => trim($m['VL']),
                'No ligne' => trim($m['No_ligne']),
                'UVC/UC' => trim($m['UVC_UC']),
                'Quant en UC' => trim($m['Quant_en_UC']),
                'Prix achat net' => trim($m['Prix_achat_net']),
                'Unite' => isset($m['UniteField']) && $m['UniteField'] ? trim($m['UniteField']) : trim($m['Type']),
                'Total prix achat net' => trim($m['Total_prix_achat_net']),
                'No. operation speciale' => '',
            ];
        }

        // Try Aswak Essalam format - more flexible parsing
        // Pattern: Article Libelle Type VL NoLigne UVCUC Quantity OperationNo Stock
        // Example: 6111175000741 THE VERT DAHMISS 41022 200G PCB 0 0095568240011 50 400.000 0

        $patternAswak = '/^
            (?P<Article>\d+)\s*
            (?P<Libelle>.+?)\s+
            (?P<Type>PCB|Piece|Pce|Pc|Unit|Unite|Kg|G)\s*
            (?P<VL>\d+)\s*
            (?P<No_ligne>\d+)\s*
            (?P<UVC_UC>\d+)\s*
            (?P<Quant_en_UC>[\d\.,]+)\s*
            (?P<No_operation>\d+)
        $/ix';

        if (preg_match($patternAswak, $raw, $m)) {
            return [
                'Article' => $m['Article'],
                'Libelle article' => trim($m['Libelle']),
                'Type U.C.' => trim($m['Type']),
                'VL' => trim($m['VL']),
                'No ligne' => trim($m['No_ligne']),
                'UVC/UC' => trim($m['UVC_UC']),
                'Quant en UC' => trim($m['Quant_en_UC']),
                'Prix achat net' => '',
                'Unite' => trim($m['Type']),
                'Total prix achat net' => '',
                'No. operation speciale' => trim($m['No_operation']),
            ];
        }

        // Fallback: Manual token parsing
        // Split by whitespace but handle numbers without spaces
        $tokens = preg_split('/\s+/', $raw);

        if (count($tokens) < 4) {
            return null; // Not enough data
        }

        $article = array_shift($tokens);

        // Sometimes article code and libelle are stuck together
        // Example: "6111175000741THE VERT" needs to be split
        if (strlen($article) > 15 && preg_match('/^(\d+)([A-Z].+)$/i', $article, $split)) {
            $article = $split[1];
            array_unshift($tokens, $split[2]);
        }

        // Find type keyword position
        $typeIndex = null;
        foreach ($tokens as $idx => $t) {
            if (preg_match('/^(PCB|Piece|Pce|Pc|Unit|Unite|Kg|G)$/i', $t)) {
                $typeIndex = $idx;
                break;
            }
        }

        if ($typeIndex === null) {
            return null;
        }

        // Extract libelle (everything before type)
        $libelleTokens = array_slice($tokens, 0, $typeIndex);
        $libelle = implode(' ', $libelleTokens);
        $type = $tokens[$typeIndex];

        // Get remaining tokens after type
        $tail = array_slice($tokens, $typeIndex + 1);

        // Try to determine format by checking if we have price-like values
        $hasPrices = false;
        if (count($tail) >= 7) {
            // Check if position 4 looks like a price (has decimal)
            if (isset($tail[4]) && preg_match('/\d+\.\d+/', $tail[4])) {
                $hasPrices = true;
            }
        }

        if ($hasPrices) {
            // Atacadao format
            return [
                'Article' => $article,
                'Libelle article' => trim($libelle),
                'Type U.C.' => trim($type),
                'VL' => $tail[0] ?? '',
                'No ligne' => $tail[1] ?? '',
                'UVC/UC' => $tail[2] ?? '',
                'Quant en UC' => $tail[3] ?? '',
                'Prix achat net' => $tail[4] ?? '',
                'Unite' => $tail[5] ?? trim($type),
                'Total prix achat net' => implode(' ', array_slice($tail, 6)),
                'No. operation speciale' => '',
            ];
        } else {
            // Aswak Essalam format
            // Expected: VL NoLigne UVCUC Quantity OperationNo [Stock]
            return [
                'Article' => $article,
                'Libelle article' => trim($libelle),
                'Type U.C.' => trim($type),
                'VL' => $tail[0] ?? '',
                'No ligne' => $tail[1] ?? '',
                'UVC/UC' => $tail[2] ?? '',
                'Quant en UC' => $tail[3] ?? '',
                'Prix achat net' => '',
                'Unite' => trim($type),
                'Total prix achat net' => '',
                'No. operation speciale' => $tail[4] ?? '',
            ];
        }
    }
}
