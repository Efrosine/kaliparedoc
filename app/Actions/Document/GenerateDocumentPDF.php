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

            // Replace placeholders with actual data
            $html = $this->replacePlaceholders($template, $document->data_json);

            // Add document number to the template
            $html = str_replace('{{document_number}}', $document->number, $html);

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

    private function replacePlaceholders($template, $data)
    {
        // Convert data to a flat array for easier placeholder replacement
        $flatData = $this->flattenData($data);

        // Replace each placeholder with its value
        foreach ($flatData as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }

        return $template;
    }

    private function flattenData($data, $prefix = '')
    {
        $result = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                // If value is an array, flatten it recursively
                $result = array_merge($result, $this->flattenData($value, $prefix . $key . '_'));
            } else {
                // Add key-value pair to the result
                $result[$prefix . $key] = $value;
            }
        }

        return $result;
    }
}
