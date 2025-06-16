<x-layouts.app>
    <x-slot name="header">
        Create Template
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('templates.index') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Back to
                Templates</a>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">Add New Template</h2>

            <form action="{{ route('templates.store') }}" method="POST">
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

                <x-wysiwyg-editor name="html_content" label="Template HTML Content" :value="old('html_content')" />

                <div class="flex justify-end mt-6">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Create Template
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>