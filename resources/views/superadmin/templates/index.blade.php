<x-layouts.app>
    <x-slot name="header">
        Templates
    </x-slot>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Manage Templates</h2>
        <a href="{{ route('templates.create') }}"
            class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            Add New Template
        </a>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document
                        Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Version
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last
                        Updated</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($templates as $template)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $template->documentType->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            v{{ $template->currentVersion ? $template->currentVersion->version : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $template->updated_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('templates.edit', $template) }}"
                                class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                            <a href="{{ route('templates.history', $template) }}"
                                class="text-gray-600 hover:text-gray-900 mr-3">History</a>
                            @if($template->currentVersion)
                                <a href="{{ route('templates.preview', $template->currentVersion) }}"
                                    class="text-green-600 hover:text-green-900">Preview</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No templates found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>