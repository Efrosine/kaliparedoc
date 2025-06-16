<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\TemplateVersion;
use App\Models\DocumentType;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TemplateController extends Controller
{
    /**
     * Display a listing of the templates.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $templates = Template::with(['documentType', 'currentVersion'])->get();
        return view('superadmin.templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new template.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $documentTypes = DocumentType::where('is_active', true)->get();
        return view('superadmin.templates.create', compact('documentTypes'));
    }

    /**
     * Store a newly created template in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_type_id' => [
                'required',
                'exists:document_types,id',
                Rule::unique('templates')->where(function ($query) use ($request) {
                    return $query->where('document_type_id', $request->document_type_id);
                })
            ],
            'html_content' => ['required', 'string'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            // Create template
            $template = Template::create([
                'document_type_id' => $validated['document_type_id'],
            ]);

            // Create initial version
            $version = TemplateVersion::create([
                'template_id' => $template->id,
                'version' => 1,
                'html_content' => $validated['html_content'],
                'updated_by' => auth()->id(),
            ]);

            // Set as current version
            $template->update(['current_version_id' => $version->id]);

            // Log the creation
            Log::create([
                'user_id' => auth()->id(),
                'action' => 'Created template',
                'model_type' => 'Template',
                'model_id' => $template->id,
            ]);
        });

        return redirect()->route('templates.index')->with('success', 'Template created successfully.');
    }

    /**
     * Show the form for editing the specified template.
     *
     * @param  \App\Models\Template  $template
     * @return \Illuminate\View\View
     */
    public function edit(Template $template)
    {
        $documentTypes = DocumentType::where('is_active', true)->get();
        return view('superadmin.templates.edit', compact('template', 'documentTypes'));
    }

    /**
     * Update the specified template in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Template  $template
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Template $template)
    {
        $validated = $request->validate([
            'html_content' => ['required', 'string'],
        ]);

        DB::transaction(function () use ($validated, $template) {
            // Create new version
            $lastVersion = $template->versions()->orderBy('version', 'desc')->first();
            $newVersion = TemplateVersion::create([
                'template_id' => $template->id,
                'version' => $lastVersion ? $lastVersion->version + 1 : 1,
                'html_content' => $validated['html_content'],
                'updated_by' => auth()->id(),
            ]);

            // Update template and set new version as current
            $template->update([
                'current_version_id' => $newVersion->id,
            ]);

            // Log the update
            Log::create([
                'user_id' => auth()->id(),
                'action' => 'Updated template',
                'model_type' => 'Template',
                'model_id' => $template->id,
            ]);
        });

        return redirect()->route('templates.index')->with('success', 'Template updated successfully.');
    }

    /**
     * Show history of template versions.
     *
     * @param  \App\Models\Template  $template
     * @return \Illuminate\View\View
     */
    public function history(Template $template)
    {
        $versions = $template->versions()->with('updatedBy')->orderBy('version', 'desc')->get();
        return view('superadmin.templates.history', compact('template', 'versions'));
    }

    /**
     * Preview a specific template version.
     *
     * @param  \App\Models\TemplateVersion  $version
     * @return \Illuminate\View\View
     */
    public function preview(TemplateVersion $version)
    {
        $template = $version->template;
        // Sample data for preview
        $sampleData = [
            'name' => 'John Doe',
            'nik' => '1234567890123456',
            'kk' => '7890123456123456',
            'address' => '123 Main Street, Village',
            'date_of_birth' => '1990-01-01',
            'gender' => 'Male',
            'religion' => 'Islam',
            'date' => now()->format('d/m/Y'),
        ];

        $html = $version->html_content;

        // Replace placeholders with sample data
        foreach ($sampleData as $key => $value) {
            $html = str_replace('{{' . $key . '}}', $value, $html);
        }

        return view('superadmin.templates.preview', compact('html', 'template', 'version'));
    }

    /**
     * Roll back to a specific version.
     *
     * @param  \App\Models\Template  $template
     * @param  \App\Models\TemplateVersion  $version
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rollback(Template $template, TemplateVersion $version)
    {
        if ($version->template_id !== $template->id) {
            return back()->with('error', 'Invalid version for this template.');
        }

        $template->update([
            'current_version_id' => $version->id,
        ]);

        // Log the rollback
        Log::create([
            'user_id' => auth()->id(),
            'action' => 'Rolled back template to version ' . $version->version,
            'model_type' => 'Template',
            'model_id' => $template->id,
        ]);

        return redirect()->route('templates.index')->with('success', 'Template rolled back to version ' . $version->version . '.');
    }
}
