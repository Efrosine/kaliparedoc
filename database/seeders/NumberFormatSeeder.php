<?php

namespace Database\Seeders;

use App\Models\NumberFormat;
use App\Models\NumberFormatVersion;
use App\Models\DocumentType;
use Illuminate\Database\Seeder;

class NumberFormatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documentTypes = DocumentType::all();

        foreach ($documentTypes as $documentType) {
            // Create number format for each document type
            $numberFormat = NumberFormat::create([
                'document_type_id' => $documentType->id,
                'format_string' => 'VILLAGE123/{{type}}/{{number}}/{{month}}/{{year}}',
            ]);

            // Create initial version
            $version = NumberFormatVersion::create([
                'number_format_id' => $numberFormat->id,
                'version' => 1,
                'format_string' => 'VILLAGE123/{{type}}/{{number}}/{{month}}/{{year}}',
                'updated_by' => 1, // Super Admin ID
                'created_at' => now(),
            ]);

            $numberFormat->update(['current_version_id' => $version->id]);
        }
    }
}
