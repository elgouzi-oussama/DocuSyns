<?php

namespace App\Services;

use Google\Cloud\DocumentAI\V1\Client\DocumentProcessorServiceClient;
use Google\Cloud\DocumentAI\V1\RawDocument;
use Google\Cloud\DocumentAI\V1\ProcessRequest;
use Illuminate\Support\Facades\Log;

class DocumentAIService
{
    private $client;
    private $processorName;

    public function __construct()
    {
        // Set up authentication via service account JSON
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . config('services.google.credentials_path'));

        $this->client = new DocumentProcessorServiceClient();

        // Processor name format: projects/{project}/locations/{location}/processors/{processor}
        $this->processorName = sprintf(
            'projects/%s/locations/%s/processors/%s',
            config('services.google.project_id'),
            config('services.google.location', 'us'), // e.g., 'us', 'eu'
            config('services.google.processor_id')
        );
    }

    /**
     * Process an image file and extract structured data
     *
     * @param string $imagePath Path to the image file
     * @param string $mimeType MIME type (e.g., 'image/png', 'image/jpeg')
     * @return array Structured data extracted from the document
     */
    public function processImage(string $imagePath, string $mimeType = 'image/png'): array
    {
        try {
            // Read the file content
            $imageContent = file_get_contents($imagePath);

            // Create a raw document
            $rawDocument = new RawDocument([
                'content' => $imageContent,
                'mime_type' => $mimeType
            ]);

            // Create the process request
            $request = new ProcessRequest([
                'name' => $this->processorName,
                'raw_document' => $rawDocument
            ]);

            // Process the document
            // dd($request);
            $response = $this->client->processDocument($request);
            dd($response);
            $document = $response->getDocument();

            // Extract structured data
            return $this->extractStructuredData($document);
        } catch (\Exception $e) {
            Log::error('Document AI processing failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Extract structured data from Document AI response
     *
     * @param \Google\Cloud\DocumentAI\V1\Document $document
     * @return array
     */
    private function extractStructuredData($document): array
    {
        $result = [
            'text' => $document->getText(),
            'pages' => [],
            'tables' => [],
            'entities' => [],
            'paragraphs' => []
        ];

        // Extract pages
        foreach ($document->getPages() as $pageIndex => $page) {
            $pageData = [
                'page_number' => $pageIndex + 1,
                'dimensions' => [
                    'width' => $page->getDimension()->getWidth(),
                    'height' => $page->getDimension()->getHeight()
                ],
                'blocks' => [],
                'tables' => []
            ];

            // Extract blocks (paragraphs, lines)
            foreach ($page->getBlocks() as $block) {
                $pageData['blocks'][] = $this->extractTextFromLayout($block->getLayout(), $document->getText());
            }

            // Extract tables from this page
            foreach ($page->getTables() as $table) {
                $tableData = $this->extractTableData($table, $document->getText());
                $pageData['tables'][] = $tableData;
                $result['tables'][] = $tableData;
            }

            $result['pages'][] = $pageData;
        }

        // Extract entities (key-value pairs, detected fields)
        foreach ($document->getEntities() as $entity) {
            $result['entities'][] = [
                'type' => $entity->getType(),
                'mention_text' => $entity->getMentionText(),
                'confidence' => $entity->getConfidence()
            ];
        }

        return $result;
    }

    /**
     * Extract table data with proper row/column structure
     *
     * @param \Google\Cloud\DocumentAI\V1\Document\Page\Table $table
     * @param string $fullText
     * @return array
     */
    private function extractTableData($table, string $fullText): array
    {
        $tableData = [
            'rows' => [],
            'header_rows' => []
        ];

        // Get table dimensions
        $numRows = 0;
        $numCols = 0;

        foreach ($table->getHeaderRows() as $headerRow) {
            foreach ($headerRow->getCells() as $cell) {
                $rowIndex = $cell->getRowSpan() > 0 ? $numRows : 0;
                $colIndex = $cell->getColSpan() > 0 ? $numCols : 0;
                $numCols = max($numCols, $colIndex + ($cell->getColSpan() ?: 1));
            }
            $numRows++;
        }

        foreach ($table->getBodyRows() as $bodyRow) {
            foreach ($bodyRow->getCells() as $cell) {
                $colIndex = $cell->getColSpan() > 0 ? 0 : 0;
                $numCols = max($numCols, $colIndex + ($cell->getColSpan() ?: 1));
            }
            $numRows++;
        }

        // Extract header rows
        foreach ($table->getHeaderRows() as $headerRow) {
            $rowData = [];
            foreach ($headerRow->getCells() as $cell) {
                $rowData[] = $this->extractTextFromLayout($cell->getLayout(), $fullText);
            }
            $tableData['header_rows'][] = $rowData;
        }

        // Extract body rows
        foreach ($table->getBodyRows() as $bodyRow) {
            $rowData = [];
            foreach ($bodyRow->getCells() as $cell) {
                $rowData[] = $this->extractTextFromLayout($cell->getLayout(), $fullText);
            }
            $tableData['rows'][] = $rowData;
        }

        return $tableData;
    }

    /**
     * Extract text from a layout segment
     *
     * @param \Google\Cloud\DocumentAI\V1\Document\Page\Layout $layout
     * @param string $fullText
     * @return string
     */
    private function extractTextFromLayout($layout, string $fullText): string
    {
        $text = '';

        if ($layout->getTextAnchor()) {
            foreach ($layout->getTextAnchor()->getTextSegments() as $segment) {
                $startIndex = $segment->getStartIndex();
                $endIndex = $segment->getEndIndex();
                $text .= substr($fullText, $startIndex, $endIndex - $startIndex);
            }
        }

        return trim($text);
    }

    /**
     * Clean up resources
     */
    public function __destruct()
    {
        if ($this->client) {
            $this->client->close();
        }
    }
}
