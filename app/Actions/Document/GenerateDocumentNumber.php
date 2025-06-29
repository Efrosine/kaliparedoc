<?php

namespace App\Actions\Document;

use App\Models\Document;
use App\Models\NumberFormat;
use Illuminate\Support\Facades\DB;

class GenerateDocumentNumber
{
    public function handle(Document $document)
    {
        return DB::transaction(function () use ($document) {
            // Lock all documents for this type, month, and year to prevent race conditions
            $documents = Document::where('type_id', $document->type_id)
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->lockForUpdate()
                ->get();

            // Cari nomor urut terbesar yang sudah ada
            $maxNumber = 0;
            foreach ($documents as $doc) {
                if ($doc->number) {
                    $parts = explode('/', $doc->number);
                    if (count($parts) >= 3 && is_numeric($parts[2])) {
                        $num = (int) $parts[2];
                        if ($num > $maxNumber) {
                            $maxNumber = $num;
                        }
                    }
                }
            }
            $nextNumber = $maxNumber + 1;

            // Get the number format for this document type
            $format = NumberFormat::where('document_type_id', $document->type_id)
                ->first();

            if (!$format) {
                throw new \Exception('No number format defined for this document type');
            }

            $formatString = $format->currentVersion->format_string;

            // Replace placeholders in the format string
            return str_replace(
                [
                    '{{village_code}}',
                    '{{type}}',
                    '{{number}}',
                    '{{month}}',
                    '{{year}}'
                ],
                [
                    'VILLAGE123', // Static village code for single village
                    $document->documentType->name,
                    str_pad($nextNumber, 4, '0', STR_PAD_LEFT),
                    now()->format('m'),
                    now()->format('Y')
                ],
                $formatString
            );
        });
    }
}
