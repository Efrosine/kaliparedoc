<x-layouts.app>
    <x-slot name="header">
        Document Types
    </x-slot>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Manage Document Types</h2>
        <a href="{{ route('document-types.create') }}"
            class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            Add New Document Type
        </a>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Version
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last
                        Updated</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($documentTypes as $documentType)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $documentType->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $documentType->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $documentType->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            v{{ $documentType->currentVersion ? $documentType->currentVersion->version : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $documentType->updated_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('document-types.edit', $documentType) }}"
                                class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                            <a href="{{ route('document-types.history', $documentType) }}"
                                class="text-gray-600 hover:text-gray-900">History</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No document types found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>