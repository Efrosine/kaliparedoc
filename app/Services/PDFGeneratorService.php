<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class PDFGeneratorService
{
    /**
     * Generate a PDF document from HTML content.
     *
     * @param string $html The HTML content to convert to PDF
     * @param array $options Optional configuration options for PDF generation
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public static function generate($html, $options = [])
    {
        try {
            $defaultOptions = [
                'paper' => 'a4',
                'orientation' => 'portrait',
                'filename' => 'document.pdf',
                'download' => false,
            ];

            $mergedOptions = array_merge($defaultOptions, $options);

            $pdf = Pdf::loadHTML($html)
                ->setPaper($mergedOptions['paper'], $mergedOptions['orientation']);

            if ($mergedOptions['download']) {
                return $pdf->download($mergedOptions['filename']);
            } else {
                return $pdf->stream($mergedOptions['filename'], ['Attachment' => false]);
            }
        } catch (\Exception $e) {
            Log::error('PDF Generation Failed: ' . $e->getMessage(), [
                'html' => $html,
                'options' => $options,
            ]);
            throw new \Exception('Failed to generate document. Please check the template.');
        }
    }

    /**
     * Replace placeholders in the template with actual values.
     * 
     * @param string $template HTML template with placeholders
     * @param array $data Data to replace placeholders
     * @return string The template with replaced values
     */
    public static function replacePlaceholders($template, $data)
    {
        // Convert data to a flat array for easier placeholder replacement
        $flatData = self::flattenData($data);
        $replacedTemplate = $template;

        foreach ($flatData as $key => $value) {
            $replacedTemplate = str_replace('{{' . $key . '}}', $value, $replacedTemplate);
        }

        return $replacedTemplate;
    }

    /**
     * Flatten a nested array into a single-level array with concatenated keys.
     * 
     * @param array $data The nested array to flatten
     * @param string $prefix Optional prefix for flattened keys
     * @return array The flattened array
     */
    private static function flattenData($data, $prefix = '')
    {
        $result = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                // If value is an array, flatten it recursively
                $result = array_merge($result, self::flattenData($value, $prefix . $key . '_'));
            } else {
                // Add key-value pair to the result
                $result[$prefix . $key] = $value;
            }
        }

        return $result;
    }
}
