<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use App\Models\DocumentTypeVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the document types.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $documentTypes = DocumentType::with('currentVersion')->get();
        return view('superadmin.document-types.index', compact('documentTypes'));
    }

    /**
     * Show the form for creating a new document type.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('superadmin.document-types.create');
    }

    /**
     * Store a newly created document type in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:document_types'],
            'is_active' => ['boolean'],
        ]);

        // Create document type with versioning
        DB::transaction(function () use ($validated, $request) {
            // Create document type
            $documentType = DocumentType::create([
                'name' => $validated['name'],
                'is_active' => $request->has('is_active'),
            ]);

            // Create initial version
            $version = DocumentTypeVersion::create([
                'document_type_id' => $documentType->id,
                'version' => 1,
                'name' => $validated['name'],
                'updated_by' => auth()->id(),
            ]);

            // Set as current version
            $documentType->update(['current_version_id' => $version->id]);

            // Log the creation
            \App\Models\Log::create([
                'user_id' => auth()->id(),
                'action' => 'Created document type',
                'model_type' => 'DocumentType',
                'model_id' => $documentType->id,
            ]);
        });

        return redirect()->route('document-types.index')->with('success', 'Document type created successfully.');
    }

    /**
     * Show the form for editing the specified document type.
     *
     * @param  \App\Models\DocumentType  $documentType
     * @return \Illuminate\View\View
     */
    public function edit(DocumentType $documentType)
    {
        return view('superadmin.document-types.edit', compact('documentType'));
    }

    /**
     * Update the specified document type in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DocumentType  $documentType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, DocumentType $documentType)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:document_types,name,' . $documentType->id],
            'is_active' => ['boolean'],
        ]);

        // Update document type with versioning
        DB::transaction(function () use ($validated, $request, $documentType) {
            // Create new version
            $lastVersion = $documentType->versions()->orderBy('version', 'desc')->first();
            $newVersion = DocumentTypeVersion::create([
                'document_type_id' => $documentType->id,
                'version' => $lastVersion ? $lastVersion->version + 1 : 1,
                'name' => $validated['name'],
                'updated_by' => auth()->id(),
            ]);

            // Update document type and set new version as current
            $documentType->update([
                'name' => $validated['name'],
                'is_active' => $request->has('is_active'),
                'current_version_id' => $newVersion->id,
            ]);

            // Log the update
            \App\Models\Log::create([
                'user_id' => auth()->id(),
                'action' => 'Updated document type',
                'model_type' => 'DocumentType',
                'model_id' => $documentType->id,
            ]);
        });

        return redirect()->route('document-types.index')->with('success', 'Document type updated successfully.');
    }

    /**
     * Show history of document type versions.
     *
     * @param  \App\Models\DocumentType  $documentType
     * @return \Illuminate\View\View
     */
    public function history(DocumentType $documentType)
    {
        $versions = $documentType->versions()->with('updatedBy')->orderBy('version', 'desc')->get();
        return view('superadmin.document-types.history', compact('documentType', 'versions'));
    }

    /**
     * Roll back to a specific version.
     *
     * @param  \App\Models\DocumentType  $documentType
     * @param  \App\Models\DocumentTypeVersion  $version
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rollback(DocumentType $documentType, DocumentTypeVersion $version)
    {
        if ($version->document_type_id !== $documentType->id) {
            return back()->with('error', 'Invalid version for this document type.');
        }

        $documentType->update([
            'name' => $version->name,
            'current_version_id' => $version->id,
        ]);

        // Log the rollback
        \App\Models\Log::create([
            'user_id' => auth()->id(),
            'action' => 'Rolled back document type to version ' . $version->version,
            'model_type' => 'DocumentType',
            'model_id' => $documentType->id,
        ]);

        return redirect()->route('document-types.index')->with('success', 'Document type rolled back to version ' . $version->version . '.');
    }
}
