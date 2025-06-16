<x-layouts.app>
    <x-slot name="header">
        Number Formats
    </x-slot>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Manage Number Formats</h2>
        <a href="{{ route('number-formats.create') }}"
            class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            Add New Number Format
        </a>
    </div>

    <div class="mb-4">
        <p>Document numbering format uses the following placeholders:</p>
        <ul class="list-disc list-inside ml-4">
            <li><code>@{{village_code}}</code> - Village code (e.g., VILLAGE123)</li>
            <li><code>@{{type}}</code> - Document type name</li>
            <li><code>@{{number}}</code> - Sequential number (resets monthly/yearly)</li>
            <li><code>@{{month}}</code> - Month in MM format</li>
            <li><code>@{{year}}</code> - Year in YYYY format</li>
        </ul>
        <p class="mt-2">Example format: <code>@{{village_code}}/@{{type}}/@{{number}}/@{{month}}/@{{year}}</code> would
            generate: <strong>VILLAGE123/KTP/0001/06/2025</strong></p>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document
                        Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Format
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
                @forelse ($numberFormats as $format)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $format->documentType->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-mono">{{ $format->format_string }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            v{{ $format->currentVersion ? $format->currentVersion->version : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $format->updated_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('number-formats.edit', $format) }}"
                                class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                            <a href="{{ route('number-formats.history', $format) }}"
                                class="text-gray-600 hover:text-gray-900">History</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No number formats found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>