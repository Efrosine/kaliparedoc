<x-layouts.app>
    <x-slot name="header">
        Edit Document Type
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('document-types.index') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Back to
                Document Types</a>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">Edit Document Type</h2>

            <form action="{{ route('document-types.update', $documentType) }}" method="POST">
                @csrf
                @method('PUT')

                <x-form-input label="Document Type Name" name="name" :value="$documentType->name" required="true" />

                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_active" value="1"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            {{ $documentType->is_active ? 'checked' : '' }}>
                        <span class="ml-2">Active</span>
                    </label>
                </div>

                <div class="flex justify-between mt-6">
                    <a href="{{ route('document-types.history', $documentType) }}"
                        class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                        View Version History
                    </a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Update Document Type
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>