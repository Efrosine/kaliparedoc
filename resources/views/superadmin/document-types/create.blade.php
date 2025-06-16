<x-layouts.app>
    <x-slot name="header">
        Create Document Type
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('document-types.index') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Back to
                Document Types</a>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">Add New Document Type</h2>

            <form action="{{ route('document-types.store') }}" method="POST">
                @csrf

                <x-form-input label="Document Type Name" name="name" required="true"
                    placeholder="Enter document type name" />

                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_active" value="1"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            checked>
                        <span class="ml-2">Active</span>
                    </label>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Create Document Type
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>