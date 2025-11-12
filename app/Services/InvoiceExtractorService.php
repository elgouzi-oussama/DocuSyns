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
          'role' => 'user',
          'content' => "
Extract only the product/article lines from the following text. 
Ignore all headers, footers, totals, and metadata.

Each article should be represented as an object in a JSON array with these exact keys:
[
  'Article',
  'Libelle article',
  'Type U.C.',
  'VL',
  'No ligne',
  'UVC/UC',
  'Quant en UC',
  'Prix achat net',
  'Unite',
  'Total prix achat net',
]

- Keep numeric values exactly as shown (with decimals).
- Do not include any text outside the article table.
- Return only valid JSON, no explanations or markdown.

Text:
$text
"
        ]

      ],
      'temperature' => 0, // deterministic output
    ]);

    // Extract model response
    $json = $response->choices[0]->message->content ?? '{}';
    $data = json_decode($json);



    return $data;
  }
}
