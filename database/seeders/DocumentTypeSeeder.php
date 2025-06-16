<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use App\Models\DocumentTypeVersion;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample document types for the village
        $documentTypes = [
            [
                'name' => 'Surat Keterangan Domisili',
                'is_active' => true,
            ],
            [
                'name' => 'Surat Keterangan Kelahiran',
                'is_active' => true,
            ],
            [
                'name' => 'Surat Keterangan Kematian',
                'is_active' => true,
            ],
            [
                'name' => 'Surat Pengantar KTP',
                'is_active' => true,
            ],
            [
                'name' => 'Surat Pengantar KK',
                'is_active' => true,
            ],
        ];

        foreach ($documentTypes as $docType) {
            $documentType = DocumentType::create([
                'name' => $docType['name'],
                'is_active' => $docType['is_active'],
            ]);

            // Create initial version
            $version = DocumentTypeVersion::create([
                'document_type_id' => $documentType->id,
                'version' => 1,
                'name' => $docType['name'],
                'updated_by' => 1, // Super Admin ID
            ]);

            $documentType->update(['current_version_id' => $version->id]);
        }
    }
}
