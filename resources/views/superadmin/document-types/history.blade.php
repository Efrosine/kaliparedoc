<x-layouts.app>
    <x-slot name="header">
        Document Type History
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('document-types.edit', $documentType) }}"
                class="text-indigo-600 hover:text-indigo-900">&larr; Back to Edit Document Type</a>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-2">Version History for: {{ $documentType->name }}</h2>
            <p class="text-gray-500 mb-6">Current version: v{{ $documentType->currentVersion->version ?? 'N/A' }}</p>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Version</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Updated By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($versions as $version)
                            <tr class="{{ $documentType->current_version_id == $version->id ? 'bg-green-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    v{{ $version->version }}
                                    @if ($documentType->current_version_id == $version->id)
                                        <span
                                            class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Current
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $version->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $version->updatedBy->name ?? 'Unknown' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $version->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if ($documentType->current_version_id != $version->id)
                                        <form
                                            action="{{ route('document-types.rollback', ['documentType' => $documentType->id, 'version' => $version->id]) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-indigo-600 hover:text-indigo-900"
                                                onclick="return confirm('Are you sure you want to roll back to this version?')">
                                                Roll Back to This Version
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>