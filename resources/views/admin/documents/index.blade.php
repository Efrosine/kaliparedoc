<x-layouts.app>
    <x-slot name="header">
        Document Requests
    </x-slot>

    <div class="py-4">
        <div class="mb-4 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Document Requests</h2>
            <a href="{{ route('admin.documents.create') }}"
                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Request New Document</a>
        </div>

        @include('components.alert')

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Document Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Submitted By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIK
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Submitted</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($documents as $document)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $document->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $document->documentType->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $document->admin ? $document->admin->name : '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $document->nik }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClass = match ($document->status) {
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'processing' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                @endphp
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                    {{ ucfirst($document->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $document->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.documents.show', $document) }}"
                                    class="px-3 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700 mr-2">View</a>
                                @if($document->status === 'completed')
                                    <a href="{{ route('admin.documents.download', $document) }}"
                                        class="px-3 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center">
                                <p class="text-gray-500 mb-4">No document requests yet</p>
                                <a href="{{ route('admin.documents.create') }}"
                                    class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">Request
                                    Your First Document</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($documents->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $documents->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>