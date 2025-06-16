<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\DocumentType;
use App\Models\DocumentTypeVersion;
use App\Models\Template;
use App\Models\TemplateVersion;
use App\Models\NumberFormat;
use App\Models\NumberFormatVersion;
use Illuminate\Support\Facades\Hash;

class DocumentSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create users if they don't exist
        if (User::count() === 0) {
            // Create a super admin
            User::create([
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]);

            // Create an admin
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]);

            // Create a client user
            User::create([
                'name' => 'Client User',
                'email' => 'client@example.com',
                'password' => Hash::make('password'),
                'role' => 'client',
            ]);
        }

        // Create document types if they don't exist
        if (DocumentType::count() === 0) {
            $documentTypes = [
                [
                    'name' => 'Surat Keterangan Domisili',
                    'is_active' => true,
                ],
                [
                    'name' => 'Surat Keterangan Usaha',
                    'is_active' => true,
                ],
                [
                    'name' => 'Surat Keterangan Tidak Mampu',
                    'is_active' => true,
                ],
            ];

            foreach ($documentTypes as $typeData) {
                $documentType = DocumentType::create([
                    'name' => $typeData['name'],
                    'is_active' => $typeData['is_active'],
                ]);

                // Create initial version
                $version = DocumentTypeVersion::create([
                    'document_type_id' => $documentType->id,
                    'version' => 1,
                    'name' => $typeData['name'],
                    'updated_by' => 1, // Super admin
                ]);

                $documentType->update(['current_version_id' => $version->id]);

                // Create template for this document type
                $this->createTemplateFor($documentType);

                // Create number format for this document type
                $this->createNumberFormatFor($documentType);
            }
        }
    }

    /**
     * Create a template for a document type
     */
    private function createTemplateFor(DocumentType $documentType): void
    {
        $template = Template::create([
            'document_type_id' => $documentType->id,
        ]);

        // HTML content based on document type
        $htmlContent = $this->getTemplateContentFor($documentType->name);

        // Create template version
        $version = TemplateVersion::create([
            'template_id' => $template->id,
            'version' => 1,
            'html_content' => $htmlContent,
            'updated_by' => 1, // Super admin
        ]);

        $template->update(['current_version_id' => $version->id]);
    }

    /**
     * Create a number format for a document type
     */
    private function createNumberFormatFor(DocumentType $documentType): void
    {
        $format_string = '{{village_code}}/{{type}}/{{number}}/{{month}}/{{year}}';

        $numberFormat = NumberFormat::create([
            'document_type_id' => $documentType->id,
            'format_string' => $format_string,
        ]);

        // Create number format version
        $version = NumberFormatVersion::create([
            'number_format_id' => $numberFormat->id,
            'version' => 1,
            'format_string' => $format_string,
            'updated_by' => 1, // Super admin
        ]);

        $numberFormat->update(['current_version_id' => $version->id]);
    }

    /**
     * Get template content based on document type name
     */
    private function getTemplateContentFor(string $documentTypeName): string
    {
        $baseStyles = '
        <style>
            body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
            .header { text-align: center; margin-bottom: 30px; }
            .content { margin-bottom: 30px; }
            .signature { margin-top: 40px; text-align: right; }
            h1 { font-size: 16pt; text-transform: uppercase; }
            table.data { width: 100%; border-collapse: collapse; }
            table.data td { padding: 5px 0; }
            .underline { text-decoration: underline; }
        </style>';

        switch ($documentTypeName) {
            case 'Surat Keterangan Domisili':
                return $baseStyles . '
                <div class="header">
                    <h1>SURAT KETERANGAN DOMISILI</h1>
                    <p>Nomor: {{document_number}}</p>
                </div>
                
                <div class="content">
                    <p>Yang bertanda tangan di bawah ini, Kepala Desa VILLAGE123 menerangkan bahwa:</p>
                    
                    <table class="data">
                        <tr>
                            <td width="200">Nama</td>
                            <td>: {{name}}</td>
                        </tr>
                        <tr>
                            <td>NIK</td>
                            <td>: {{nik}}</td>
                        </tr>
                        <tr>
                            <td>No. Kartu Keluarga</td>
                            <td>: {{kk}}</td>
                        </tr>
                        <tr>
                            <td>Tempat, Tanggal Lahir</td>
                            <td>: {{birth_place}}, {{birth_date}}</td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td>: {{gender}}</td>
                        </tr>
                        <tr>
                            <td>Agama</td>
                            <td>: {{religion}}</td>
                        </tr>
                        <tr>
                            <td>Status Perkawinan</td>
                            <td>: {{marital_status}}</td>
                        </tr>
                        <tr>
                            <td>Pekerjaan</td>
                            <td>: {{occupation}}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>: {{address}}</td>
                        </tr>
                    </table>
                    
                    <p>Adalah benar-benar warga yang berdomisili di alamat tersebut sesuai dengan data yang terdapat pada Kantor Desa kami.</p>
                    
                    <p>Demikian Surat Keterangan Domisili ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
                </div>
                
                <div class="signature">
                    <p>VILLAGE123, ' . date('d F Y') . '</p>
                    <p>Kepala Desa VILLAGE123</p>
                    <br><br><br>
                    <p class="underline">NAMA KEPALA DESA</p>
                </div>';

            case 'Surat Keterangan Usaha':
                return $baseStyles . '
                <div class="header">
                    <h1>SURAT KETERANGAN USAHA</h1>
                    <p>Nomor: {{document_number}}</p>
                </div>
                
                <div class="content">
                    <p>Yang bertanda tangan di bawah ini, Kepala Desa VILLAGE123 menerangkan bahwa:</p>
                    
                    <table class="data">
                        <tr>
                            <td width="200">Nama</td>
                            <td>: {{name}}</td>
                        </tr>
                        <tr>
                            <td>NIK</td>
                            <td>: {{nik}}</td>
                        </tr>
                        <tr>
                            <td>No. Kartu Keluarga</td>
                            <td>: {{kk}}</td>
                        </tr>
                        <tr>
                            <td>Tempat, Tanggal Lahir</td>
                            <td>: {{birth_place}}, {{birth_date}}</td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td>: {{gender}}</td>
                        </tr>
                        <tr>
                            <td>Agama</td>
                            <td>: {{religion}}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>: {{address}}</td>
                        </tr>
                    </table>
                    
                    <p>Benar yang bersangkutan memiliki usaha yang bergerak di bidang perdagangan/jasa yang terletak di wilayah Desa VILLAGE123.</p>
                    
                    <p>Demikian Surat Keterangan Usaha ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
                </div>
                
                <div class="signature">
                    <p>VILLAGE123, ' . date('d F Y') . '</p>
                    <p>Kepala Desa VILLAGE123</p>
                    <br><br><br>
                    <p class="underline">NAMA KEPALA DESA</p>
                </div>';

            case 'Surat Keterangan Tidak Mampu':
                return $baseStyles . '
                <div class="header">
                    <h1>SURAT KETERANGAN TIDAK MAMPU</h1>
                    <p>Nomor: {{document_number}}</p>
                </div>
                
                <div class="content">
                    <p>Yang bertanda tangan di bawah ini, Kepala Desa VILLAGE123 menerangkan bahwa:</p>
                    
                    <table class="data">
                        <tr>
                            <td width="200">Nama</td>
                            <td>: {{name}}</td>
                        </tr>
                        <tr>
                            <td>NIK</td>
                            <td>: {{nik}}</td>
                        </tr>
                        <tr>
                            <td>No. Kartu Keluarga</td>
                            <td>: {{kk}}</td>
                        </tr>
                        <tr>
                            <td>Tempat, Tanggal Lahir</td>
                            <td>: {{birth_place}}, {{birth_date}}</td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td>: {{gender}}</td>
                        </tr>
                        <tr>
                            <td>Agama</td>
                            <td>: {{religion}}</td>
                        </tr>
                        <tr>
                            <td>Pekerjaan</td>
                            <td>: {{occupation}}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>: {{address}}</td>
                        </tr>
                    </table>
                    
                    <p>Adalah benar-benar warga dari keluarga tidak mampu yang terdaftar di Desa VILLAGE123.</p>
                    
                    <p>Demikian Surat Keterangan Tidak Mampu ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
                </div>
                
                <div class="signature">
                    <p>VILLAGE123, ' . date('d F Y') . '</p>
                    <p>Kepala Desa VILLAGE123</p>
                    <br><br><br>
                    <p class="underline">NAMA KEPALA DESA</p>
                </div>';

            default:
                return $baseStyles . '
                <div class="header">
                    <h1>SURAT KETERANGAN</h1>
                    <p>Nomor: {{document_number}}</p>
                </div>
                
                <div class="content">
                    <p>Yang bertanda tangan di bawah ini, Kepala Desa VILLAGE123 menerangkan bahwa:</p>
                    
                    <table class="data">
                        <tr>
                            <td width="200">Nama</td>
                            <td>: {{name}}</td>
                        </tr>
                        <tr>
                            <td>NIK</td>
                            <td>: {{nik}}</td>
                        </tr>
                        <tr>
                            <td>No. Kartu Keluarga</td>
                            <td>: {{kk}}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>: {{address}}</td>
                        </tr>
                    </table>
                    
                    <p>Adalah benar-benar warga yang terdaftar di Desa VILLAGE123.</p>
                    
                    <p>Demikian Surat Keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
                </div>
                
                <div class="signature">
                    <p>VILLAGE123, ' . date('d F Y') . '</p>
                    <p>Kepala Desa VILLAGE123</p>
                    <br><br><br>
                    <p class="underline">NAMA KEPALA DESA</p>
                </div>';
        }
    }
}
