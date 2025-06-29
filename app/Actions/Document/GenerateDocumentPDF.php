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
            // --- Generate document number with blank if not completed ---
            $numberFormat = $document->documentType->numberFormat->currentVersion->format_string ?? null;
            if ($numberFormat) {
                if ($document->status === 'completed') {
                    $numberValue = isset($document->number) ? $this->extractNumberComponent($document->number) : '';
                } else {
                    $numberValue = '      '; // 6 spaces for blank number
                }
                $documentData['document_number'] = str_replace(
                    ['{{number}}'],
                    [$numberValue],
                    $numberFormat
                );
            } else {
                $documentData['document_number'] = "        ";
            }

            // --- Add KK and anggota data ---
            $kk = null;
            $anggota = collect();
            if (!empty($document->kk)) {
                $kk = \App\Models\KartuKeluarga::where('no_kk', $document->kk)->first();
                if ($kk) {
                    $documentData['nama_kepala_keluarga'] = $kk->nama_kepala_keluarga;
                    $documentData['alamat_jalan'] = $kk->alamat_jalan;
                    $documentData['rt'] = $kk->rt;
                    $documentData['rw'] = $kk->rw;
                    $documentData['kode_pos'] = $kk->kode_pos;
                    $anggota = \App\Models\AnggotaKeluarga::where('no_kk', $kk->no_kk)->orderBy('no_urut')->get();
                }
            }
            // Cari anggota keluarga yang NIK-nya sama dengan dokumen
            $anggotaTerkait = $anggota->firstWhere('nik', $document->nik);
            if ($anggotaTerkait) {
                $documentData['name'] = $anggotaTerkait->nama;
                $documentData['birth_place'] = $anggotaTerkait->tempat_lahir;
                $documentData['birth_date'] = isset($anggotaTerkait->tanggal_lahir) ? \Carbon\Carbon::parse($anggotaTerkait->tanggal_lahir)->format('d-m-Y') : '';
                $documentData['gender'] = $anggotaTerkait->jenis_kelamin;
                $documentData['religion'] = $anggotaTerkait->agama;
                $documentData['marital_status'] = $anggotaTerkait->status_perkawinan;
                $documentData['occupation'] = $anggotaTerkait->pekerjaan;
            }
            // Always set these
            $documentData['address'] = $kk ? $kk->alamat_jalan : '';
            $documentData['kk'] = $document->kk;
            $documentData['no_kk'] = $document->kk;

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

    /**
     * Extract the number component from a document number string (e.g. 510.4/0001/35.07.11.2002/2023 => 0001)
     */
    private function extractNumberComponent($documentNumber)
    {
        $parts = explode('/', $documentNumber);
        return isset($parts[1]) ? $parts[1] : '';
    }
}
