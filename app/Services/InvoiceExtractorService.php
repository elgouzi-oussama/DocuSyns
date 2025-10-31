<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;

class InvoiceExtractorService
{
    public function extractFields(string $text): array
    {
        $response = OpenAI::chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are an assistant that extracts structured invoice data. Always return valid JSON.'
                ],
                [
                    'role' => 'user',
                    'content' => "Extract the following fields from this invoice text and return JSON only.
            
            Field definitions:
            - reference_commande: the purchase order number
            - date_commande: the order date
            - nom_fournisseur: the supplier name
            - code_fournisseur: the supplier’s unique code if present
            - montant_ht: total excluding taxes
            - montant_tva: tax amount
            - montant_ttc: total including taxes
            - commande_par: the person, department, or company who made the order
            - commande_a: the person, department, or address the order was sent to or delivered to
            
                    Text:\n" . $text
                ],
            ],
            'temperature' => 0, // deterministic output
        ]);

        $json = $response->choices[0]->message->content ?? '{}';
        $cleanJson = preg_replace('/.*?({.*}).*/s', '$1', $json);


        return json_decode($cleanJson, true);
    }
}
