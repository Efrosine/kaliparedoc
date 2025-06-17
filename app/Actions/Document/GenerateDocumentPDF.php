<?php

namespace App\Actions\Document;

use App\Models\Document;
use App\Services\PDFGeneratorService;
use Illuminate\Support\Facades\Log;

class GenerateDocumentPDF
{
    public function handle(Document $document)
    {
        try {
            // Get the template HTML content
            $template = $document->documentType->template->currentVersion->html_content;

            // Make a copy of the data to add the document number
            $documentData = $document->data_json;
            $documentData['document_number'] = $document->number;

            // Replace placeholders with actual data
            $html = PDFGeneratorService::replacePlaceholders($template, $documentData);

            // Generate and return PDF
            return PDFGeneratorService::generate($html);
        } catch (\Exception $e) {
            Log::error('PDF Generation Error: ' . $e->getMessage(), [
                'document_id' => $document->id,
                'exception' => $e
            ]);
            throw $e;
        }
    }
}
