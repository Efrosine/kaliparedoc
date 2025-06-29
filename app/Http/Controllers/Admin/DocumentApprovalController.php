<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Document\CreateDocumentRequest;
use App\Actions\Document\HandleDocumentApproval;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentType;
use Illuminate\Http\Request;

class DocumentApprovalController extends Controller
{
    /**
     * Display a listing of documents pending approval
     */
    public function index(Request $request)
    {
        // Get documents with filter options
        $query = Document::with(['user', 'documentType'])
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when(!$request->has('status'), function ($query) {
                return $query->whereIn('status', ['pending', 'processing']);
            })
            ->latest();

        // Check for overdue documents (3+ days old)
        if ($request->has('overdue')) {
            $query->where('created_at', '<=', now()->subDays(3));
        }

        $documents = $query->paginate(10);

        return view('admin.documents.index', compact('documents'));
    }

    /**
     * Display the specified document for approval
     */
    public function show(Document $document)
    {
        // Load necessary relationships
        $document->load(['user', 'documentType']);

        return view('admin.documents.show', compact('document'));
    }

    /**
     * Preview the document before approval
     */
    public function preview(Document $document, Request $request)
    {
        // Ambil data dokumen
        $document->load(['user', 'documentType']);
        $data = $document->data_json ?? [];

        // Ambil data KK dan anggota keluarga jika ada input KK
        $kk = null;
        $anggota = collect();
        if (!empty($document->kk)) {
            $kk = \App\Models\KartuKeluarga::where('no_kk', $document->kk)->first();
            if ($kk) {
                $anggota = \App\Models\AnggotaKeluarga::where('no_kk', $kk->no_kk)->orderBy('no_urut')->get();
            }
        }

        // Jika admin ingin mengedit data tambahan KK/anggota, tampilkan form
        if ($request->isMethod('post')) {
            // Cek apakah form edit KK atau anggota yang dikirim
            if ($request->has('kk')) {
                // Validasi dan update data KK
                $validated = $request->validate([
                    'kk.nama_kepala_keluarga' => 'required|string',
                    'kk.alamat_jalan' => 'required|string',
                    'kk.rt' => 'required|string',
                    'kk.rw' => 'required|string',
                    'kk.kode_pos' => 'required|string',
                ]);
                if ($kk) {
                    $kk->update($validated['kk']);
                }
                return redirect()->route('admin.documents.preview', $document)->with('success', 'Data KK diperbarui.');
            } elseif ($request->has('anggota')) {
                // Validasi dan update data anggota keluarga
                $validated = $request->validate([
                    'anggota.*.nama' => 'required|string',
                    'anggota.*.nik' => 'required|string',
                    'anggota.*.jenis_kelamin' => 'required|string',
                    'anggota.*.tempat_lahir' => 'required|string',
                    'anggota.*.tanggal_lahir' => 'required|date',
                    'anggota.*.golongan_darah' => 'nullable|string',
                    'anggota.*.agama' => 'required|string',
                    'anggota.*.status_perkawinan' => 'required|string',
                    'anggota.*.status_hubungan_dalam_keluarga' => 'required|string',
                    'anggota.*.pendidikan' => 'nullable|string',
                    'anggota.*.pekerjaan' => 'nullable|string',
                    'anggota.*.nama_ibu' => 'nullable|string',
                    'anggota.*.nama_ayah' => 'nullable|string',
                ]);
                if ($anggota->count()) {
                    foreach ($anggota as $i => $agt) {
                        if (isset($validated['anggota'][$i])) {
                            $agt->update($validated['anggota'][$i]);
                        }
                    }
                }
                return redirect()->route('admin.documents.preview', $document)->with('success', 'Data anggota keluarga diperbarui.');
            }
        }
        // Cari anggota keluarga yang NIK-nya sama dengan dokumen
        $anggotaTerkait = $anggota->firstWhere('nik', $document->nik);

        // Ambil template HTML
        $template = $document->documentType->template->currentVersion->html_content;
        // Replace placeholders dari anggota keluarga terkait (bukan data_json)
        $template = str_replace('{{name}}', $anggotaTerkait->nama ?? '', $template);
        $template = str_replace('{{nik}}', $document->nik, $template);
        $template = str_replace('{{birth_place}}', $anggotaTerkait->tempat_lahir ?? '', $template);
        $template = str_replace('{{birth_date}}', isset($anggotaTerkait->tanggal_lahir) ? \Carbon\Carbon::parse($anggotaTerkait->tanggal_lahir)->format('d-m-Y') : '', $template);
        $template = str_replace('{{gender}}', $anggotaTerkait->jenis_kelamin ?? '', $template);
        $template = str_replace('{{religion}}', $anggotaTerkait->agama ?? '', $template);
        $template = str_replace('{{marital_status}}', $anggotaTerkait->status_perkawinan ?? '', $template);
        $template = str_replace('{{occupation}}', $anggotaTerkait->pekerjaan ?? '', $template);
        $template = str_replace('{{address}}', $kk ? $kk->alamat_jalan : '', $template);
        $template = str_replace('{{kk}}', $document->kk, $template);
        $template = str_replace('{{no_kk}}', $document->kk, $template);
        // Replace data KK jika ada
        if ($kk) {
            $template = str_replace('{{nama_kepala_keluarga}}', $kk->nama_kepala_keluarga, $template);
            $template = str_replace('{{alamat_jalan}}', $kk->alamat_jalan, $template);
            $template = str_replace('{{rt}}', $kk->rt, $template);
            $template = str_replace('{{rw}}', $kk->rw, $template);
            $template = str_replace('{{kode_pos}}', $kk->kode_pos, $template);
        }
        $template = str_replace('{{document_number}}', '[DOCUMENT NUMBER WILL BE GENERATED ON APPROVAL]', $template);

        return view('admin.documents.preview', [
            'document' => $document,
            'preview' => $template,
            'kk' => $kk,
            'anggota' => $anggota,
        ]);
    }

    /**
     * Approve the document
     */
    public function approve(Document $document, Request $request)
    {
        if ($document->status === 'pending') {
            try {
                $handler = new HandleDocumentApproval();
                $handler->approve($document);
                return redirect()->route('admin.documents.index')
                    ->with('success', 'Document has been approved and finalized with number: ' . $document->number);
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to approve document: ' . $e->getMessage());
            }
        }
        return back()->with('error', 'Only pending documents can be approved.');
    }

    /**
     * Reject the document
     */
    public function reject(Document $document, Request $request)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        try {
            // Process document rejection using the action class
            $handler = new HandleDocumentApproval();
            $handler->reject($document, $validated['reason']);

            return redirect()->route('admin.documents.index')
                ->with('success', 'Document has been rejected');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject document: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new document request (by admin)
     */
    public function create()
    {
        $documentTypes = DocumentType::where('is_active', true)->get();
        return view('admin.documents.create', compact('documentTypes'));
    }

    /**
     * Store a newly created document request (by admin)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => [
                'required',
                'digits:16',
                // 'regex:/^[0-9]{6}[0-1][0-9]{1}[0-3][0-9]{1}[0-9]{4}$/',
            ],
            'kk' => ['required', 'digits:16'],
            'document_type_id' => ['required', 'exists:document_types,id'],
        ]);

        try {
            $action = new CreateDocumentRequest();
            $document = $action->handle($validated + $request->except(['_token']));
            return redirect()->route('admin.documents.show', $document)
                ->with('success', 'Document request submitted successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to submit document request: ' . $e->getMessage());
        }
    }

    /**
     * Download the document as PDF (admin)
     */
    public function download(Document $document)
    {
        if ($document->status !== 'completed') {
            return back()->with('error', 'Only completed documents can be downloaded');
        }
        try {
            $action = new \App\Actions\Document\GenerateDocumentPDF();
            return $action->handle($document);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to generate document: ' . $e->getMessage());
        }
    }
}
