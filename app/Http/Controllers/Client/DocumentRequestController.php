<?php

namespace App\Http\Controllers\Client;

use App\Actions\Document\CreateDocumentRequest;
use App\Actions\Document\GenerateDocumentPDF;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DocumentRequestController extends Controller
{
    /**
     * Display a listing of the client's document requests
     */
    public function index()
    {
        $documents = Document::where('client_id', Auth::id())
            ->with('documentType')
            ->latest()
            ->paginate(10);

        return view('client.documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new document request
     */
    public function create()
    {
        $documentTypes = DocumentType::where('is_active', true)->get();

        return view('client.documents.create', compact('documentTypes'));
    }

    /**
     * Store a newly created document request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => [

                'digits:16',

            ],
            'kk' => ['required', 'digits:16'],
            'document_type_id' => ['required', 'exists:document_types,id,is_active,1'],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
        ]);

        try {
            // Use the action class to create document request
            $action = new CreateDocumentRequest();
            $document = $action->handle($validated);

            return redirect()->route('client.documents.show', $document)
                ->with('success', 'Document request submitted successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to submit document request: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified document request
     */
    public function show(Document $document)
    {
        // Ensure the client can only see their own documents
        if ($document->client_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $document->load('documentType');

        return view('client.documents.show', compact('document'));
    }

    /**
     * Download the document as PDF
     */
    public function download(Document $document)
    {
        // Ensure the client can only download their own completed documents
        if ($document->client_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($document->status !== 'completed') {
            return back()->with('error', 'Only completed documents can be downloaded');
        }

        try {
            // Use the action class to generate PDF
            $action = new GenerateDocumentPDF();
            return $action->handle($document);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to generate document: ' . $e->getMessage());
        }
    }
}
