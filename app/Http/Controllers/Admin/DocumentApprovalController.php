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
    public function preview(Document $document)
    {
        // Get the template HTML content
        $template = $document->documentType->template->currentVersion->html_content;

        // Replace placeholders with actual data
        $data = $document->data_json;
        foreach ($data as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }

        // Add document number placeholder (will be replaced with actual number on approval)
        $template = str_replace('{{document_number}}', '[DOCUMENT NUMBER WILL BE GENERATED ON APPROVAL]', $template);

        return view('admin.documents.preview', [
            'document' => $document,
            'preview' => $template
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
