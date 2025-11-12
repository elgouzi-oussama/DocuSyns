<?php

namespace App\Services;

use Illuminate\Support\Str;

/**
 * InvoiceParser
 *
 * Parse a flat array of strings (like lines extracted from a PDF/CSV/whatever)
 * into an array of associative records representing articles.
 */
class InvoiceParserForArticles
{
    /**
     * Parse lines into structured article records.
     *
     * @param array $lines Flat array of strings (the raw data you showed)
     * @return array List of associative arrays (records)
     */
    public static function parse(array $lines, $text): array
    {

        $aswak = collect($lines)->contains(fn($line) => Str::contains($line, 'ASWAK ASSALAM'));
        $kazyon = collect($lines)->contains(fn($line) => Str::contains($line, 'KAZYON'));


        // dd($lines);
        if ($aswak) {
            $startWord = 'Qte en stock';
            $endWord = 'Date de livraison';
            $collecting = false;
            $articleBlocks = [];
            $currentBlock = [];

            // 1️⃣ Extract all blocks between Article … Date de livraison
            foreach ($lines as $line) {
                if (stripos($line, $startWord) !== false) {
                    $collecting = true;
                    continue;
                }

                if (stripos($line, $endWord) !== false) {
                    $collecting = false;
                    if (!empty($currentBlock)) {
                        $articleBlocks[] = $currentBlock;
                        $currentBlock = [];
                    }
                    continue;
                }

                if ($collecting) {
                    // Detect a new article starting with 13 digits
                    if (preg_match('/^\d{13}/', $line) && !empty($currentBlock)) {
                        $articleBlocks[] = $currentBlock;
                        $currentBlock = [];
                    }
                    $currentBlock[] = trim($line);
                }
            }

            // Close last block
            if (!empty($currentBlock)) {
                $articleBlocks[] = $currentBlock;
            }

            // 2️⃣ Parse each article block
            $results = [];

            foreach ($articleBlocks as $block) {
                $text = trim(implode(' ', $block));
                $text = preg_replace('/\s+/', ' ', $text); // normalize spaces

                // Match flexible formats:
                // article(13d) name(anything) type_uc letters then VL + no_ligne + qtys
                preg_match(
                    '/(?<article>\d{13})(?<libelle>.*?)\s+(?<type_uc>[A-Z]+)\s*(?<vl>\d)\s*(?<no_ligne>\d{10,})\s+(?<uvc_uc>\d+)\s+(?<quant_uc>[\d.]+)\s+(?<qte_stock>\d+)/',
                    $text,
                    $m
                );

                if (!empty($m)) {
                    $results[] = [
                        'article'   => trim($m['article'] ?? ''),
                        'libelle'   => trim($m['libelle'] ?? ''),
                        'type_uc'   => trim($m['type_uc'] ?? ''),
                        'vl'        => $m['vl'] === '' ? '0' : trim($m['vl']),
                        'no_ligne'  => trim($m['no_ligne'] ?? ''),
                        'uvc_uc'    => trim($m['uvc_uc'] ?? ''),
                        'quant_uc'  => trim($m['quant_uc'] ?? ''),
                        'qte_stock' => trim($m['qte_stock'] ?? ''),
                    ];
                }
            }

            return ($results);
        }


        // 🟢 CASE 2: KAZYON RETAIL
        if ($kazyon) {
            // $text = trim(preg_replace('/\s+/', ' ', implode(' ', $lines))); // combine all lines

            $results = [];

            if (preg_match_all(
                '/(?<article_code>\d{9})\s*(?<libelle>[A-Z0-9\s\-]+?)\s*(?<ean>\d{13})\s*(?<date_livraison>\d{2}\.\d{2}\.\d{4})\s*(?<unit_par_colis>\d+)\s*(?<quantite_colis>\d+)\s*(?<total_unites>\d+)/',
                $text,
                $matches,
                PREG_SET_ORDER
            )) {
                foreach ($matches as $m) {
                    $results[] = [
                        'article_code'    => trim($m['article_code'] ?? ''),
                        'libelle'         => trim($m['libelle'] ?? ''),
                        'ean'             => trim($m['ean'] ?? ''),
                        'date_livraison'  => trim($m['date_livraison'] ?? ''),
                        'unit_par_colis'  => trim($m['unit_par_colis'] ?? ''),
                        'quantite_colis'  => trim($m['quantite_colis'] ?? ''),
                        'total_unites'    => trim($m['total_unites'] ?? ''),
                    ];
                }
            }

            return $results;
        }


        //
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
            if (preg_match('/^\d+\b/', $line) && !preg_match('/\//', $line)) {
                // Additional validation: check if line contains expected article structure
                // Articles should have "Piece" or similar unit type
                $testLine = $line;
                $j = $i + 1;

                // Peek ahead to build complete record for validation
                while ($j < $n) {
                    $next = trim($lines[$j]);
                    if ($next === '') {
                        $j++;
                        continue;
                    }
                    if (preg_match('/^\d+\b/', $next) && !preg_match('/\//', $next)) {
                        break;
                    }
                    if (preg_match('/^TOTAL\b|^TVA\b|^MONTANT\b|^Date\s+de\s+livraison/i', $next)) {
                        break;
                    }
                    $testLine .= ' ' . $next;
                    $j++;
                }

                // Validate that this looks like an article record
                if (preg_match('/\b(Piece|Pce|Pc|Unit|Unite|Kg|G)\b/i', $testLine)) {
                    $current = preg_replace('/\s+/', ' ', trim($testLine));
                    $rawRecords[] = $current;
                    $i = $j - 1;
                }
            }
        }

        $items = [];
        foreach ($rawRecords as $raw) {
            // Try a regex that captures the known pieces.
            $pattern = '/^
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

            $parsed = false;
            if (preg_match($pattern, $raw, $m)) {
                $items[] = [
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
                ];
                $parsed = true;
            }

            if (!$parsed) {
                // Fallback: token-based parsing
                $tokens = preg_split('/\s+/', $raw);
                $article = array_shift($tokens);

                // Find first token that looks like a known Type
                $typeIndex = null;
                foreach ($tokens as $idx => $t) {
                    if (preg_match('/^(Piece|Pce|Pc|Unit|Unite|Kg|G)$/i', $t)) {
                        $typeIndex = $idx;
                        break;
                    }
                }

                if ($typeIndex === null) {
                    continue; // Skip invalid records
                }

                $libelleTokens = array_slice($tokens, 0, $typeIndex);
                $libelle = implode(' ', $libelleTokens);
                $type = $tokens[$typeIndex];
                $tail = array_slice($tokens, $typeIndex + 1);

                $VL = $tail[0] ?? '';
                $No_ligne = $tail[1] ?? '';
                $UVC_UC = $tail[2] ?? '';
                $Quant_en_UC = $tail[3] ?? '';
                $Prix_achat_net = $tail[4] ?? '';
                $maybeUnit = $tail[5] ?? null;

                // Handle glued price and unit
                if ($Prix_achat_net !== '' && preg_match('/^([0-9\.,]+)([A-Za-z]+.*)$/', $Prix_achat_net, $pm)) {
                    $Prix_achat_net = $pm[1];
                    $maybeUnit = $pm[2] . ($maybeUnit ? ' ' . $maybeUnit : '');
                }

                $consumed = 5 + ($maybeUnit ? 1 : 0);
                $remaining = array_slice($tail, $consumed);
                $Total_prix_achat_net = implode(' ', $remaining);

                $items[] = [
                    'Article' => $article,
                    'Libelle article' => trim($libelle),
                    'Type U.C.' => trim($type),
                    'VL' => trim($VL),
                    'No ligne' => trim($No_ligne),
                    'UVC/UC' => trim($UVC_UC),
                    'Quant en UC' => trim($Quant_en_UC),
                    'Prix achat net' => trim($Prix_achat_net),
                    'Unite' => $maybeUnit ? trim($maybeUnit) : trim($type),
                    'Total prix achat net' => trim($Total_prix_achat_net),
                ];
            }
        }

        return $items;
    }
}
