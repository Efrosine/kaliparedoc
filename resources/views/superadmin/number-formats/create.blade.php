<x-layouts.app>
    <x-slot name="header">
        Create Number Format
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('number-formats.index') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Back to
                Number Formats</a>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">Add New Number Format</h2>

            <div class="mb-4">
                <p>Document numbering format uses the following placeholders:</p>
                <ul class="list-disc list-inside ml-4">
                    <li><code>@{{village_code}}</code> - Village code (e.g., VILLAGE123)</li>
                    <li><code>@{{type}}</code> - Document type name</li>
                    <li><code>@{{number}}</code> - Sequential number (resets monthly/yearly)</li>
                    <li><code>@{{month}}</code> - Month in MM format</li>
                    <li><code>@{{year}}</code> - Year in YYYY format</li>
                </ul>
                <p class="mt-2">Example format:
                    <code>@{{village_code}}/@{{type}}/@{{number}}/@{{month}}/@{{year}}</code>
                    would generate: <strong>VILLAGE123/KTP/0001/06/2025</strong>
                </p>
            </div>

            <form action="{{ route('number-formats.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="document_type_id" class="block text-sm font-medium text-gray-700">Document Type</label>
                    <select id="document_type_id" name="document_type_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required>
                        <option value="">Select a document type</option>
                        @foreach($documentTypes as $type)
                            <option value="{{ $type->id }}" {{ old('document_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('document_type_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <x-form-input label="Format String" name="format_string"
                    value="@{{village_code}}/@{{type}}/@{{number}}/@{{month}}/@{{year}}" required="true"
                    placeholder="Enter format string with placeholders" />

                <div class="flex justify-end mt-6">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Create Number Format
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>