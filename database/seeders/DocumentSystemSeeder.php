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
        }

        // Create document types if they don't exist
        if (DocumentType::count() === 0) {
            $documentTypes = [
                [
                    'name' => 'SURAT KETERANGAN DOMISILI USAHA/ PERUSAHAAN',
                    'prefix' => '510.4',
                    'year' => '2025',
                ],
                [
                    'name' => 'SURAT KETERANGAN DOMISILI LEMBAGA',
                    'prefix' => '421.1',
                    'year' => '2025',
                ],
                [
                    'name' => 'SURAT KETERANGAN DOMISILI WARGA',
                    'prefix' => '470',
                    'year' => '2025',
                ],
                [
                    'name' => 'SURAT KETERANGAN PASUTRI DI LUAR NEGERI ATAU BEKERJA',
                    'prefix' => '470',
                    'year' => '2025',
                ],
                [
                    'name' => 'SURAT KETERANGAN USAHA',
                    'prefix' => '518.3',
                    'year' => '2025',
                ],
                [
                    'name' => 'SURAT PENGANTAR LAPORAN KEHILANGAN',
                    'prefix' => '337',
                    'year' => '2025',
                ],
                [
                    'name' => 'SURAT KETERANGAN TIDAK MAMPU SISWA',
                    'prefix' => '463',
                    'year' => '2025',
                ],
                [
                    'name' => 'SURAT LAPORAN KEHILANGAN',
                    'prefix' => '337',
                    'year' => '2025',
                ],
            ];

            foreach ($documentTypes as $typeData) {
                $documentType = DocumentType::create([
                    'name' => $typeData['name'],
                    'is_active' => true,
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
                $this->createNumberFormatFor($documentType, $typeData['prefix'], $typeData['year']);
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
    private function createNumberFormatFor(DocumentType $documentType, string $prefix, string $year): void
    {
        $format_string = $prefix . '/{{number}}/35.07.11.2002/' . $year;

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
        body { font-family: "Bookman Old Style", serif; margin: 0; padding: 20px; }
        .kop { text-align: center; margin-bottom: 20px; }
        .kop img { width: 80px; height: auto; position: absolute; left: 20px; top: 20px; }
        .kop h1 { margin: 0; font-size: 16pt; }
        .kop h2 { margin: 0; font-size: 14pt; }
        .kop p { margin: 2px 0; font-size: 10pt; }
        hr { border: 1px solid black; margin: 10px 0; }
        .header { text-align: center; margin-bottom: 15px; }
        .header h2 { text-decoration: underline; margin: 0; }
        .content { margin-bottom: 20px; }
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.data td { padding: 5px 0; }
        .signature { margin-top: 40px; text-align: right; }
        .underline { text-decoration: underline; }
    </style>';

        switch ($documentTypeName) {
            case 'SURAT KETERANGAN DOMISILI USAHA/ PERUSAHAAN':
                return $baseStyles . '
               <div class="kop">
                <img src="https://upload.wikimedia.org/wikipedia/commons/d/d9/Logo_Kabupaten_Malang_-_Seal_of_Malang_Regency.svg" alt="Logo">
                <h1>PEMERINTAH KABUPATEN MALANG</h1>
                <h2>KECAMATAN KALIPARE</h2>
                <h2><b>DESA KALIPARE</b></h2>
                <p>Alamat: Jalan Soekarno-Hatta No. 577 Kalipare</p>
                <p>Website: desa-kalipare.malangkab.go.id | Email: desakalipare@gmail.com</p>
                <p>Kode Pos 65166</p>
                <hr>
            </div>
            
            <div class="header">
                <h2>SURAT KETERANGAN DOMISILI USAHA/ PERUSAHAAN</h2>
                <p>No. Reg : {{document_number}}</p>
            </div>
            
            <div class="content">
                <p>Yang bertanda tangan dibawah ini atas nama Kepala Desa Kalipare, Kecamatan Kalipare, Kabupaten Malang, menerangkan dengan sebenarnya bahwa:</p>
                
                <table class="data">
                    <tr><td width="200">Nama</td><td>: {{name}}</td></tr>
                    <tr><td>NIK</td><td>: {{nik}}</td></tr>
                    <tr><td>Tempat, Tanggal Lahir</td><td>: {{birth_place}}, {{birth_date}}</td></tr>
                    <tr><td>Jenis Kelamin</td><td>: {{gender}}</td></tr>
                    <tr><td>Agama</td><td>: {{religion}}</td></tr>
                    <tr><td>Status Perkawinan</td><td>: {{marital_status}}</td></tr>
                    <tr><td>Pekerjaan</td><td>: {{occupation}}</td></tr>
                    <tr><td>Kewarganegaraan</td><td>: {{citizenship}}</td></tr>
                    <tr><td>Alamat</td><td>: {{address}}</td></tr>
                </table>

                <p>Adalah benar-benar penduduk Desa Kalipare, Kecamatan Kalipare, Kabupaten Malang yang sekarang berdomisili dan memiliki/membuka usaha sebagaimana tersebut di bawah:</p>

                <table class="data">
                    <tr><td width="250">Nama Usaha/Perusahaan</td><td>: {{business_name}}</td></tr>
                    <tr><td>Jenis Usaha/Klasifikasi</td><td>: {{business_type}}</td></tr>
                    <tr><td>Alamat Usaha/Perusahaan</td><td>: {{business_address}}</td></tr>
                    <tr><td>Status Tempat Usaha</td><td>: {{business_status}}</td></tr>
                    <tr><td>Penggunaan Bangunan</td><td>: {{business_usage}}</td></tr>
                    <tr><td>Pimpinan Usaha</td><td>: {{business_leader}}</td></tr>
                </table>

                <p>Adapun surat keterangan ini dipergunakan khusus untuk <b><i>{{purpose}}</i></b>.</p>

                <p>Demikian surat keterangan ini diberikan, untuk dipergunakan sebagaimana mestinya. Surat keterangan ini berlaku sampai dengan: <b><u>{{valid_until}}</u></b>.</p>
            </div>
            
            <div class="signature">
                <p>Kalipare, ' . date('d F Y') . '</p>
                <p>a.n Kepala Desa Kalipare</p>
                <p>Sekretaris Desa</p>
                <br><br><br>
                <p class="underline">AHMAD YUSRO</p>
            </div>';

            case 'SURAT KETERANGAN DOMISILI LEMBAGA':
                return $baseStyles . '
    <div class="kop">
        <img src="https://upload.wikimedia.org/wikipedia/commons/d/d9/Logo_Kabupaten_Malang_-_Seal_of_Malang_Regency.svg" alt="Logo">
        <h1>PEMERINTAH KABUPATEN MALANG</h1>
        <h2>KECAMATAN KALIPARE</h2>
        <h2><b>DESA KALIPARE</b></h2>
        <p>Alamat: Jalan Soekarno-Hatta No. 577 Kalipare</p>
        <p>Website: desa-kalipare.malangkab.go.id | Email: desakalipare@gmail.com</p>
        <p>Kode Pos 65166</p>
        <hr>
    </div>
    
    <div class="header">
        <h2>SURAT KETERANGAN DOMISILI LEMBAGA</h2>
        <p>Nomor: {{document_number}}</p>
    </div>
    
    <div class="content">
        <p>Yang bertanda tangan dibawah ini:</p>
        
        <table class="data">
            <tr><td width="200">Nama</td><td>: AHMAD YUSRO</td></tr>
            <tr><td>Jabatan</td><td>: Sekretaris Desa</td></tr>
            <tr><td>Alamat</td><td>: Jl. Soekarno-Hatta No. 577 Desa Kalipare</td></tr>
        </table>

        <p>Menerangkan dengan sebenarnya bahwa:</p>
        
        <table class="data">
            <tr><td width="200">Nama Lembaga</td><td>: {{institution_name}}</td></tr>
            <tr><td>Jenis Lembaga</td><td>: {{institution_type}}</td></tr>
            <tr><td>NSS/NIS</td><td>: {{nss_nis}}</td></tr>
            <tr><td>NPSN</td><td>: {{npsn}}</td></tr>
            <tr><td>Tanggal/Tahun Berdiri</td><td>: {{established_date}}</td></tr>
            <tr><td>Nama Kepala Lembaga</td><td>: {{institution_leader}}</td></tr>
            <tr><td>Alamat Lembaga</td><td>: {{institution_address}}</td></tr>
        </table>

        <p>Sepanjang pengetahuan dan pengamatan kami, benar bahwa Instansi/Lembaga tersebut benar-benar berdomisili di wilayah Pemerintah Desa Kalipare, Kecamatan Kalipare, Kabupaten Malang serta sampai dengan surat keterangan ini diterbitkan masih aktif melaksanakan kegiatan belajar mengajar secara tatap muka maupun daring.</p>
        
        <p>Surat keterangan ini dipergunakan khusus untuk <b>{{purpose}}</b>.</p>
        
        <p>Demikian surat keterangan domisili ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>
    
    <div class="signature">
        <p>Kalipare, ' . date('d F Y') . '</p>
        <p>a.n Kepala Desa Kalipare</p>
        <p>Sekretaris Desa</p>
        <br><br><br>
        <p class="underline">AHMAD YUSRO</p>
    </div>';


            case 'SURAT KETERANGAN DOMISILI WARGA':
                return $baseStyles . '
    <div class="kop">
        <img src="https://upload.wikimedia.org/wikipedia/commons/d/d9/Logo_Kabupaten_Malang_-_Seal_of_Malang_Regency.svg" alt="Logo">
        <h1>PEMERINTAH KABUPATEN MALANG</h1>
        <h2>KECAMATAN KALIPARE</h2>
        <h2><b>DESA KALIPARE</b></h2>
        <p>Alamat: Jalan Soekarno-Hatta No. 577 Kalipare</p>
        <p>Website: desa-kalipare.malangkab.go.id | Email: desakalipare@gmail.com</p>
        <p>Kode Pos 65166</p>
        <hr>
    </div>
    
    <div class="header">
        <h2>SURAT KETERANGAN DOMISILI</h2>
        <p>No. Reg : {{document_number}}</p>
    </div>
    
    <div class="content">
        <p>Yang bertanda tangan dibawah ini atas nama Kepala Desa Kalipare, Kecamatan Kalipare, Kabupaten Malang, menerangkan dengan sebenarnya bahwa:</p>
        
        <table class="data">
            <tr><td width="200">Nama Lengkap</td><td>: {{name}}</td></tr>
            <tr><td>NIK</td><td>: {{nik}}</td></tr>
            <tr><td>Tempat Lahir</td><td>: {{birth_place}}</td></tr>
            <tr><td>Tanggal Lahir</td><td>: {{birth_date}}</td></tr>
            <tr><td>Umur</td><td>: {{age}} Tahun</td></tr>
            <tr><td>Jenis Kelamin</td><td>: {{gender}}</td></tr>
            <tr><td>Agama</td><td>: {{religion}}</td></tr>
            <tr><td>Status Perkawinan</td><td>: {{marital_status}}</td></tr>
            <tr><td>Pekerjaan</td><td>: {{occupation}}</td></tr>
            <tr><td>Kewarganegaraan</td><td>: {{citizenship}}</td></tr>
            <tr><td>Alamat</td><td>: {{address}}</td></tr>
        </table>

        <p>Bahwa orang tersebut di atas adalah benar-benar penduduk Desa Kalipare, Kecamatan Kalipare, Kabupaten Malang;</p>
        <p>Bahwa orang tersebut di atas adalah benar-benar berdomisili pada alamat tersebut di atas;</p>
        
        <p>Surat keterangan ini dipergunakan khusus untuk: <b>{{purpose}}</b>.</p>
        
        <p>Demikian surat keterangan ini dibuat atas dasar yang sebenarnya untuk menjadikan periksa dan dipergunakan sebagaimana mestinya bagi yang berkepentingan.</p>
    </div>
    
    <div class="signature">
        <p>Kalipare, ' . date('d F Y') . '</p>
        <p>a.n Kepala Desa Kalipare</p>
        <p>Sekretaris Desa</p>
        <br><br><br>
        <p class="underline">AHMAD YUSRO</p>
    </div>';


            case 'SURAT KETERANGAN PASUTRI DI LUAR NEGERI ATAU BEKERJA':
                return $baseStyles . '
    <div class="kop">
        <img src="https://upload.wikimedia.org/wikipedia/commons/d/d9/Logo_Kabupaten_Malang_-_Seal_of_Malang_Regency.svg" alt="Logo">
        <h1>PEMERINTAH KABUPATEN MALANG</h1>
        <h2>KECAMATAN KALIPARE</h2>
        <h2><b>DESA KALIPARE</b></h2>
        <p>Alamat: Jalan Soekarno-Hatta No. 577 Kalipare</p>
        <p>Website: desa-kalipare.malangkab.go.id | Email: desakalipare@gmail.com</p>
        <p>Kode Pos 65166</p>
        <hr>
    </div>
    
    <div class="header">
        <h2>SURAT KETERANGAN</h2>
        <p>No. Reg : {{document_number}}</p>
    </div>
    
    <div class="content">
        <p>Yang bertanda tangan dibawah ini atas nama Kepala Desa Kalipare, Kecamatan Kalipare, Kabupaten Malang, menerangkan dengan sebenarnya bahwa penduduk kami sebagai berikut:</p>
        
        <table class="data">
            <tr><td width="200">Nama</td><td>: {{wife_name}}</td></tr>
            <tr><td>NIK</td><td>: {{wife_nik}}</td></tr>
            <tr><td>Tempat Lahir</td><td>: {{wife_birth_place}}</td></tr>
            <tr><td>Tanggal Lahir</td><td>: {{wife_birth_date}}</td></tr>
            <tr><td>Jenis Kelamin</td><td>: {{wife_gender}}</td></tr>
            <tr><td>Agama</td><td>: {{wife_religion}}</td></tr>
            <tr><td>Status</td><td>: {{wife_marital_status}}</td></tr>
            <tr><td>Pekerjaan</td><td>: {{wife_occupation}}</td></tr>
            <tr><td>Alamat Lengkap</td><td>: {{wife_address}}</td></tr>
        </table>

        <p>Adapun hubungan keluarga orang tersebut di atas adalah istri sah dari seorang suami:</p>

        <table class="data">
            <tr><td width="200">Nama</td><td>: {{husband_name}}</td></tr>
            <tr><td>NIK</td><td>: {{husband_nik}}</td></tr>
            <tr><td>Tempat Lahir</td><td>: {{husband_birth_place}}</td></tr>
            <tr><td>Tanggal Lahir</td><td>: {{husband_birth_date}}</td></tr>
            <tr><td>Jenis Kelamin</td><td>: {{husband_gender}}</td></tr>
            <tr><td>Agama</td><td>: {{husband_religion}}</td></tr>
            <tr><td>Status</td><td>: {{husband_marital_status}}</td></tr>
            <tr><td>Pekerjaan</td><td>: {{husband_occupation}}</td></tr>
            <tr><td>Alamat Lengkap</td><td>: {{husband_address}}</td></tr>
        </table>

        <p>Menerangkan dengan sebenarnya bahwa suami orang tersebut di atas saat ini sedang bekerja di <b>{{work_location}}</b>. Adapun surat keterangan ini khusus dipergunakan untuk: <b>{{purpose}}</b>.</p>

        <p>Demikian surat keterangan ini dibuat atas dasar yang sebenarnya untuk menjadikan periksa dan dipergunakan sebagaimana mestinya bagi yang berkepentingan.</p>
    </div>
    
    <div class="signature">
        <p>Kalipare, ' . date('d F Y') . '</p>
        <table class="data">
            <tr>
                <td width="33%" style="text-align: center;">
                    Pemohon I<br><br><br><br>
                    <b><u>{{wife_name}}</u></b>
                </td>
                <td width="33%" style="text-align: center;">
                    Mengetahui,<br>Ketua RT {{rt}}<br><br><br><br>
                    <b><u>{{rt_head}}</u></b>
                </td>
                <td width="33%" style="text-align: center;">
                    Mengetahui,<br>Ketua RW {{rw}}<br><br><br><br>
                    <b><u>{{rw_head}}</u></b>
                </td>
            </tr>
        </table>

        <p style="margin-top: 30px;">a.n. Kepala Desa Kalipare<br>Sekretaris Desa<br><br><br><b><u>AHMAD YUSRO</u></b></p>
    </div>';

            case 'SURAT KETERANGAN USAHA':
                return $baseStyles . '
    <div class="kop">
        <img src="https://upload.wikimedia.org/wikipedia/commons/d/d9/Logo_Kabupaten_Malang_-_Seal_of_Malang_Regency.svg" alt="Logo">
        <h1>PEMERINTAH KABUPATEN MALANG</h1>
        <h2>KECAMATAN KALIPARE</h2>
        <h2><b>DESA KALIPARE</b></h2>
        <p>Alamat: Jalan Soekarno-Hatta No. 577 Kalipare</p>
        <p>Website: desa-kalipare.malangkab.go.id | Email: desakalipare@gmail.com</p>
        <p>Kode Pos 65166</p>
        <hr>
    </div>
    
    <div class="header">
        <h2>SURAT KETERANGAN USAHA</h2>
        <p>Reg. No : {{document_number}}</p>
    </div>
    
    <div class="content">
        <p>Yang bertanda tangan di bawah ini atas nama Kepala Desa Kalipare, Kecamatan Kalipare, Kabupaten Malang, menerangkan bahwa:</p>
        
        <table class="data">
            <tr><td width="200">Nama</td><td>: {{name}}</td></tr>
            <tr><td>NIK</td><td>: {{nik}}</td></tr>
            <tr><td>Tempat Lahir</td><td>: {{birth_place}}</td></tr>
            <tr><td>Tanggal Lahir</td><td>: {{birth_date}}</td></tr>
            <tr><td>Umur</td><td>: {{age}} Tahun</td></tr>
            <tr><td>Jenis Kelamin</td><td>: {{gender}}</td></tr>
            <tr><td>Agama</td><td>: {{religion}}</td></tr>
            <tr><td>Status Perkawinan</td><td>: {{marital_status}}</td></tr>
            <tr><td>Pekerjaan</td><td>: {{occupation}}</td></tr>
            <tr><td>Kewarganegaraan</td><td>: {{citizenship}}</td></tr>
            <tr><td>Alamat Lengkap</td><td>: {{address}}</td></tr>
        </table>

        <p>Bahwa orang tersebut di atas benar-benar penduduk Desa Kalipare, Kecamatan Kalipare, Kabupaten Malang.</p>
        
        <table class="data">
            <tr><td width="200">Memiliki Usaha</td><td>: {{business_type}}</td></tr>
            <tr><td>Nama Usaha</td><td>: {{business_name}}</td></tr>
            <tr><td>Tempat Usaha</td><td>: {{business_address}}</td></tr>
            <tr><td>Pemasaran</td><td>: {{market_area}}</td></tr>
            <tr><td>Berdiri Sejak</td><td>: {{established_year}}</td></tr>
        </table>

        <p>Keterangan ini dipergunakan khusus untuk: <b>{{purpose}}</b>.</p>

        <p>Demikian keterangan ini dibuat atas dasar yang sebenarnya untuk menjadikan periksa dan dipergunakan sebagaimana mestinya bagi yang berkepentingan.</p>
    </div>
    
    <div class="signature">
        <p>Kalipare, ' . date('d F Y') . '</p>
        <p>a.n. Kepala Desa Kalipare</p>
        <p>Sekretaris Desa</p>
        <br><br><br>
        <p class="underline">AHMAD YUSRO</p>
    </div>';

            case 'SURAT PENGANTAR LAPORAN KEHILANGAN':
                return $baseStyles . '
    <div class="kop">
        <img src="https://upload.wikimedia.org/wikipedia/commons/d/d9/Logo_Kabupaten_Malang_-_Seal_of_Malang_Regency.svg" alt="Logo">
        <h1>PEMERINTAH KABUPATEN MALANG</h1>
        <h2>KECAMATAN KALIPARE</h2>
        <h2><b>DESA KALIPARE</b></h2>
        <p>Alamat: Jalan Soekarno-Hatta No. 577 Kalipare</p>
        <p>Website: desa-kalipare.malangkab.go.id | Email: desakalipare@gmail.com</p>
        <p>Kode Pos 65166</p>
        <hr>
    </div>
    
    <div class="header">
        <h2>SURAT PENGANTAR LAPORAN KEHILANGAN</h2>
        <p>No. Reg : {{document_number}}</p>
    </div>
    
    <div class="content">
        <p>Yang bertanda tangan di bawah ini, saya warga Desa Kalipare, Kecamatan Kalipare, Kabupaten Malang:</p>
        
        <table class="data">
            <tr><td width="200">Nama</td><td>: {{name}}</td></tr>
            <tr><td>NIK</td><td>: {{nik}}</td></tr>
            <tr><td>Tempat Lahir</td><td>: {{birth_place}}</td></tr>
            <tr><td>Tanggal Lahir</td><td>: {{birth_date}}</td></tr>
            <tr><td>Umur</td><td>: {{age}} Tahun</td></tr>
            <tr><td>Jenis Kelamin</td><td>: {{gender}}</td></tr>
            <tr><td>Agama</td><td>: {{religion}}</td></tr>
            <tr><td>Status Perkawinan</td><td>: {{marital_status}}</td></tr>
            <tr><td>Pekerjaan</td><td>: {{occupation}}</td></tr>
            <tr><td>Kewarganegaraan</td><td>: {{citizenship}}</td></tr>
            <tr><td>Alamat</td><td>: {{address}}</td></tr>
        </table>

        <p>Saya melaporkan kepada Pemerintah Desa TELAH KEHILANGAN barang berupa:</p>

        <table class="data">
            <tr><td width="200">Nama Barang</td><td>: {{item_name}}</td></tr>
            <tr><td>Atas Nama</td><td>: {{item_owner}}</td></tr>
            <tr><td>No. ID</td><td>: {{item_id}}</td></tr>
            <tr><td>Hari</td><td>: {{loss_day}}</td></tr>
            <tr><td>Tanggal</td><td>: {{loss_date}}</td></tr>
            <tr><td>Pukul</td><td>: {{loss_time}}</td></tr>
            <tr><td>Lokasi Kejadian</td><td>: {{loss_location}}</td></tr>
        </table>

        <p>Demikian surat keterangan ini dibuat atas dasar sebenarnya untuk menjadikan periksa dan dipergunakan sebagaimana mestinya bagi yang berkepentingan.</p>
    </div>
    
    <div class="signature">
        <p>Kalipare, {{issued_date}}</p>
        <table class="data">
            <tr>
                <td width="50%" style="text-align: center;">
                    Tanda Tangan Pelapor,<br><br><br><br>
                    <b><u>{{name}}</u></b>
                </td>
                <td width="50%" style="text-align: center;">
                    Mengetahui,<br>a.n. Kepala Desa Kalipare<br>Sekretaris Desa<br><br><br>
                    <b><u>AHMAD YUSRO</u></b>
                </td>
            </tr>
        </table>
    </div>';

            case 'SURAT KETERANGAN TIDAK MAMPU SISWA':
                return $baseStyles . '
    <div class="kop">
        <img src="https://upload.wikimedia.org/wikipedia/commons/d/d9/Logo_Kabupaten_Malang_-_Seal_of_Malang_Regency.svg" alt="Logo">
        <h1>PEMERINTAH KABUPATEN MALANG</h1>
        <h2>KECAMATAN KALIPARE</h2>
        <h2><b>DESA KALIPARE</b></h2>
        <p>Alamat: Jalan Soekarno-Hatta No. 577 Kalipare</p>
        <p>Website: desa-kalipare.malangkab.go.id | Email: desakalipare@gmail.com</p>
        <p>Kode Pos 65166</p>
        <hr>
    </div>
    
    <div class="header">
        <h2>SURAT KETERANGAN TIDAK MAMPU</h2>
        <p>No. Reg : {{document_number}}</p>
    </div>
    
    <div class="content">
        <p>Yang bertanda tangan di bawah ini atas nama Kepala Desa Kalipare, Kecamatan Kalipare, Kabupaten Malang, menerangkan dengan sebenarnya bahwa penduduk kami sebagai berikut:</p>
        
        <table class="data">
            <tr><td width="200">Nama Lengkap</td><td>: {{parent_name}}</td></tr>
            <tr><td>NIK</td><td>: {{parent_nik}}</td></tr>
            <tr><td>ID DTKS</td><td>: {{dtks_id}}</td></tr>
            <tr><td>Tempat, Tanggal Lahir</td><td>: {{parent_birth_place}}, {{parent_birth_date}}</td></tr>
            <tr><td>Umur</td><td>: {{parent_age}} Tahun</td></tr>
            <tr><td>Hub. Keluarga</td><td>: {{family_relationship}}</td></tr>
            <tr><td>Jenis Kelamin</td><td>: {{parent_gender}}</td></tr>
            <tr><td>Agama</td><td>: {{parent_religion}}</td></tr>
            <tr><td>Pekerjaan</td><td>: {{parent_occupation}}</td></tr>
            <tr><td>Alamat</td><td>: {{parent_address}}</td></tr>
        </table>

        <p>Adalah benar-benar dari keluarga yang tidak mampu, dan penghasilan tidak menentu.</p>

        <p>Adapun surat keterangan ini dipergunakan khusus untuk kelengkapan administrasi pendaftaran sekolah bagi anaknya:</p>

        <table class="data">
            <tr><td width="200">Nama Lengkap</td><td>: {{student_name}}</td></tr>
            <tr><td>NIK</td><td>: {{student_nik}}</td></tr>
            <tr><td>Tempat, Tanggal Lahir</td><td>: {{student_birth_place}}, {{student_birth_date}}</td></tr>
            <tr><td>Umur</td><td>: {{student_age}} Tahun</td></tr>
            <tr><td>Jenis Kelamin</td><td>: {{student_gender}}</td></tr>
            <tr><td>Agama</td><td>: {{student_religion}}</td></tr>
            <tr><td>Pekerjaan</td><td>: {{student_occupation}}</td></tr>
            <tr><td>Kelas/Semester</td><td>: {{student_class}}</td></tr>
            <tr><td>Sekolah/Universitas</td><td>: {{current_school}}</td></tr>
            <tr><td>Sekolah/Universitas Tujuan</td><td>: {{target_school}}</td></tr>
        </table>

        <p>Demikian untuk menjadikan periksa dan dipergunakan sebagaimana mestinya bagi yang berkepentingan.</p>
    </div>
    
    <div class="signature">
        <p>Kalipare, {{issued_date}}</p>
        <table class="data">
            <tr>
                <td width="33%" style="text-align: center;">
                    Pemohon I<br><br><br><br>
                    <b><u>{{parent_name}}</u></b>
                </td>
                <td width="33%" style="text-align: center;">
                    Mengetahui,<br>Ketua RT {{rt}}<br><br><br><br>
                    <b><u>{{rt_head}}</u></b>
                </td>
                <td width="33%" style="text-align: center;">
                    Mengetahui,<br>Ketua RW {{rw}}<br><br><br><br>
                    <b><u>{{rw_head}}</u></b>
                </td>
            </tr>
        </table>

        <p style="margin-top: 30px;">a.n. Kepala Desa Kalipare<br>Sekretaris Desa<br><br><br>
        <b><u>AHMAD YUSRO</u></b></p>
    </div>';

            case 'SURAT LAPORAN KEHILANGAN':
                return $baseStyles . '
                <div class="header">
                    <h1>SURAT LAPORAN KEHILANGAN</h1>
                    <p>Nomor: {{document_number}}</p>
                </div>
                
                <div class="content">
                    <p>Yang bertanda tangan di bawah ini, Kepala Desa VILLAGE123 menerangkan bahwa:</p>
                    
                    <table class="data">
                        <tr>
                            <td width="200">Nama Pelapor</td>
                            <td>: {{reporter_name}}</td>
                        </tr>
                        <tr>
                            <td>NIK Pelapor</td>
                            <td>: {{reporter_nik}}</td>
                        </tr>
                        <tr>
                            <td>Alamat Pelapor</td>
                            <td>: {{reporter_address}}</td>
                        </tr>
                        <tr>
                            <td>Jenis Kehilangan</td>
                            <td>: {{loss_type}}</td>
                        </tr>
                        <tr>
                            <td>Deskripsi Kehilangan</td>
                            <td>: {{loss_description}}</td>
                        </tr>
                    </table>
                    
                    <p>Adalah benar bahwa pelapor telah melaporkan kehilangan tersebut di atas kepada kami.</p>
                    
                    <p>Demikian Surat Laporan Kehilangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
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
