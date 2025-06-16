<?php

namespace Database\Seeders;

use App\Models\Template;
use App\Models\TemplateVersion;
use App\Models\DocumentType;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documentTypes = DocumentType::all();

        foreach ($documentTypes as $documentType) {
            // Create template for each document type
            $template = Template::create([
                'document_type_id' => $documentType->id,
            ]);

            // Base template HTML content depending on document type
            $html = $this->getTemplateForDocumentType($documentType->name);

            // Create initial version
            $version = TemplateVersion::create([
                'template_id' => $template->id,
                'version' => 1,
                'html_content' => $html,
                'updated_by' => 1, // Super Admin ID
            ]);

            $template->update(['current_version_id' => $version->id]);
        }
    }

    /**
     * Get template HTML based on document type
     *
     * @param string $documentType
     * @return string
     */
    private function getTemplateForDocumentType(string $documentType): string
    {
        $templates = [
            'Surat Keterangan Domisili' => '<div style="font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h2 style="margin-bottom: 5px;">PEMERINTAH KABUPATEN LOREM IPSUM</h2>
                    <h2 style="margin-bottom: 5px;">KECAMATAN DOLOR SIT AMET</h2>
                    <h1 style="margin-bottom: 5px;">DESA CONSECTETUR</h1>
                    <p>Jalan Desa No. 123, Kecamatan Dolor Sit Amet, Kabupaten Lorem Ipsum 12345</p>
                    <hr style="border: 2px solid black;">
                </div>
                
                <h3 style="text-align: center; text-decoration: underline; margin-bottom: 20px;">SURAT KETERANGAN DOMISILI</h3>
                <p style="text-align: center; margin-bottom: 20px;">Nomor: {{number}}</p>
                
                <p>Yang bertanda tangan di bawah ini Kepala Desa Consectetur, Kecamatan Dolor Sit Amet, Kabupaten Lorem Ipsum, menerangkan dengan sebenarnya bahwa:</p>
                
                <div style="margin-left: 30px; margin-bottom: 20px;">
                    <table>
                        <tr>
                            <td style="width: 150px;">Nama</td>
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
                            <td>Jenis Kelamin</td>
                            <td>: {{gender}}</td>
                        </tr>
                        <tr>
                            <td>Tempat, Tgl Lahir</td>
                            <td>: {{date_of_birth}}</td>
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
                </div>
                
                <p>Berdasarkan data dan keterangan yang ada pada kami, bahwa nama tersebut di atas benar-benar penduduk yang berdomisili di Desa Consectetur, Kecamatan Dolor Sit Amet, Kabupaten Lorem Ipsum.</p>
                
                <p>Demikian surat keterangan ini dibuat dengan sebenarnya dan diberikan kepada yang bersangkutan untuk dapat dipergunakan sebagaimana mestinya.</p>
                
                <div style="text-align: right; margin-top: 40px;">
                    <p>Consectetur, {{date}}</p>
                    <p>Kepala Desa Consectetur</p>
                    <br><br><br>
                    <p style="font-weight: bold; text-decoration: underline;">ADIPISCING ELIT</p>
                </div>
            </div>',

            'Surat Keterangan Kelahiran' => '<div style="font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h2 style="margin-bottom: 5px;">PEMERINTAH KABUPATEN LOREM IPSUM</h2>
                    <h2 style="margin-bottom: 5px;">KECAMATAN DOLOR SIT AMET</h2>
                    <h1 style="margin-bottom: 5px;">DESA CONSECTETUR</h1>
                    <p>Jalan Desa No. 123, Kecamatan Dolor Sit Amet, Kabupaten Lorem Ipsum 12345</p>
                    <hr style="border: 2px solid black;">
                </div>
                
                <h3 style="text-align: center; text-decoration: underline; margin-bottom: 20px;">SURAT KETERANGAN KELAHIRAN</h3>
                <p style="text-align: center; margin-bottom: 20px;">Nomor: {{number}}</p>
                
                <p>Yang bertanda tangan di bawah ini Kepala Desa Consectetur, Kecamatan Dolor Sit Amet, Kabupaten Lorem Ipsum, menerangkan dengan sebenarnya bahwa pada:</p>
                
                <div style="margin-left: 30px; margin-bottom: 20px;">
                    <table>
                        <tr>
                            <td style="width: 150px;">Hari/Tanggal</td>
                            <td>: ...</td>
                        </tr>
                        <tr>
                            <td>Tempat</td>
                            <td>: ...</td>
                        </tr>
                    </table>
                </div>
                
                <p>Telah lahir seorang anak:</p>
                
                <div style="margin-left: 30px; margin-bottom: 20px;">
                    <table>
                        <tr>
                            <td style="width: 150px;">Nama</td>
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
                            <td>Jenis Kelamin</td>
                            <td>: {{gender}}</td>
                        </tr>
                        <tr>
                            <td>Tempat, Tgl Lahir</td>
                            <td>: {{date_of_birth}}</td>
                        </tr>
                    </table>
                </div>
                
                <p>Demikian surat keterangan ini dibuat dengan sebenarnya dan diberikan kepada yang bersangkutan untuk dapat dipergunakan sebagaimana mestinya.</p>
                
                <div style="text-align: right; margin-top: 40px;">
                    <p>Consectetur, {{date}}</p>
                    <p>Kepala Desa Consectetur</p>
                    <br><br><br>
                    <p style="font-weight: bold; text-decoration: underline;">ADIPISCING ELIT</p>
                </div>
            </div>',

            'default' => '<div style="font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h2 style="margin-bottom: 5px;">PEMERINTAH KABUPATEN LOREM IPSUM</h2>
                    <h2 style="margin-bottom: 5px;">KECAMATAN DOLOR SIT AMET</h2>
                    <h1 style="margin-bottom: 5px;">DESA CONSECTETUR</h1>
                    <p>Jalan Desa No. 123, Kecamatan Dolor Sit Amet, Kabupaten Lorem Ipsum 12345</p>
                    <hr style="border: 2px solid black;">
                </div>
                
                <h3 style="text-align: center; text-decoration: underline; margin-bottom: 20px;">SURAT KETERANGAN</h3>
                <p style="text-align: center; margin-bottom: 20px;">Nomor: {{number}}</p>
                
                <p>Yang bertanda tangan di bawah ini Kepala Desa Consectetur, Kecamatan Dolor Sit Amet, Kabupaten Lorem Ipsum, menerangkan dengan sebenarnya bahwa:</p>
                
                <div style="margin-left: 30px; margin-bottom: 20px;">
                    <table>
                        <tr>
                            <td style="width: 150px;">Nama</td>
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
                            <td>Jenis Kelamin</td>
                            <td>: {{gender}}</td>
                        </tr>
                        <tr>
                            <td>Tempat, Tgl Lahir</td>
                            <td>: {{date_of_birth}}</td>
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
                </div>
                
                <p>Demikian surat keterangan ini dibuat dengan sebenarnya dan diberikan kepada yang bersangkutan untuk dapat dipergunakan sebagaimana mestinya.</p>
                
                <div style="text-align: right; margin-top: 40px;">
                    <p>Consectetur, {{date}}</p>
                    <p>Kepala Desa Consectetur</p>
                    <br><br><br>
                    <p style="font-weight: bold; text-decoration: underline;">ADIPISCING ELIT</p>
                </div>
            </div>'
        ];

        return $templates[$documentType] ?? $templates['default'];
    }
}
