<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\NumberFormat;
use App\Models\NumberFormatVersion;
use App\Models\DocumentType;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class NumberFormatController extends Controller
{
    /**
     * Display a listing of the number formats.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $numberFormats = NumberFormat::with(['documentType', 'currentVersion'])->get();
        return view('superadmin.number-formats.index', compact('numberFormats'));
    }

    /**
     * Show the form for creating a new number format.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $documentTypes = DocumentType::where('is_active', true)->get();
        return view('superadmin.number-formats.create', compact('documentTypes'));
    }

    /**
     * Store a newly created number format in storage.
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
                Rule::unique('number_formats')->where(function ($query) use ($request) {
                    return $query->where('document_type_id', $request->document_type_id);
                })
            ],
            'format_string' => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            // Create number format
            $numberFormat = NumberFormat::create([
                'document_type_id' => $validated['document_type_id'],
                'format_string' => $validated['format_string'],
            ]);

            // Create initial version
            $version = NumberFormatVersion::create([
                'number_format_id' => $numberFormat->id,
                'version' => 1,
                'format_string' => $validated['format_string'],
                'updated_by' => auth()->id(),
            ]);

            // Set as current version
            $numberFormat->update(['current_version_id' => $version->id]);

            // Log the creation
            Log::create([
                'user_id' => auth()->id(),
                'action' => 'Created number format',
                'model_type' => 'NumberFormat',
                'model_id' => $numberFormat->id,
            ]);
        });

        return redirect()->route('number-formats.index')->with('success', 'Number format created successfully.');
    }

    /**
     * Show the form for editing the specified number format.
     *
     * @param  \App\Models\NumberFormat  $numberFormat
     * @return \Illuminate\View\View
     */
    public function edit(NumberFormat $numberFormat)
    {
        $documentTypes = DocumentType::where('is_active', true)->get();
        return view('superadmin.number-formats.edit', compact('numberFormat', 'documentTypes'));
    }

    /**
     * Update the specified number format in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NumberFormat  $numberFormat
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, NumberFormat $numberFormat)
    {
        $validated = $request->validate([
            'format_string' => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated, $numberFormat) {
            // Create new version
            $lastVersion = $numberFormat->versions()->orderBy('version', 'desc')->first();
            $newVersion = NumberFormatVersion::create([
                'number_format_id' => $numberFormat->id,
                'version' => $lastVersion ? $lastVersion->version + 1 : 1,
                'format_string' => $validated['format_string'],
                'updated_by' => auth()->id(),
            ]);

            // Update number format and set new version as current
            $numberFormat->update([
                'format_string' => $validated['format_string'],
                'current_version_id' => $newVersion->id,
            ]);

            // Log the update
            Log::create([
                'user_id' => auth()->id(),
                'action' => 'Updated number format',
                'model_type' => 'NumberFormat',
                'model_id' => $numberFormat->id,
            ]);
        });

        return redirect()->route('number-formats.index')->with('success', 'Number format updated successfully.');
    }

    /**
     * Show history of number format versions.
     *
     * @param  \App\Models\NumberFormat  $numberFormat
     * @return \Illuminate\View\View
     */
    public function history(NumberFormat $numberFormat)
    {
        $versions = $numberFormat->versions()->with('updatedBy')->orderBy('version', 'desc')->get();
        return view('superadmin.number-formats.history', compact('numberFormat', 'versions'));
    }

    /**
     * Roll back to a specific version.
     *
     * @param  \App\Models\NumberFormat  $numberFormat
     * @param  \App\Models\NumberFormatVersion  $version
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rollback(NumberFormat $numberFormat, NumberFormatVersion $version)
    {
        if ($version->number_format_id !== $numberFormat->id) {
            return back()->with('error', 'Invalid version for this number format.');
        }

        $numberFormat->update([
            'format_string' => $version->format_string,
            'current_version_id' => $version->id,
        ]);

        // Log the rollback
        Log::create([
            'user_id' => auth()->id(),
            'action' => 'Rolled back number format to version ' . $version->version,
            'model_type' => 'NumberFormat',
            'model_id' => $numberFormat->id,
        ]);

        return redirect()->route('number-formats.index')->with('success', 'Number format rolled back to version ' . $version->version . '.');
    }
}
