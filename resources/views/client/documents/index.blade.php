<x-layouts.app>
    <x-slot name="header">
        My Documents
    </x-slot>

    <div class="mb-4 flex justify-between items-center">
        <a href="{{ route('client.documents.create') }}"
            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">Request
            New Document</a>
    </div>

    @include('components.alert')

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="bg-gray-50 px-4 py-3 border-b">
            <h5 class="text-lg font-semibold">Document Request History</h5>
        </div>
        <div class="p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Document Type</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                NIK</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Submission Date</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($documents as $document)
                            @php
                                $statusClass = match ($document->status) {
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'processing' => 'bg-blue-100 text-blue-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $document->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $document->documentType->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $document->nik }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ ucfirst($document->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $document->created_at->format('d M Y, H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('client.documents.show', $document) }}"
                                        class="px-3 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700 mr-2">
                                        View
                                    </a>
                                    @if($document->status === 'completed')
                                        <a href="{{ route('client.documents.download', $document) }}"
                                            class="px-3 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center">
                                    <p class="text-gray-500 mb-4">No document requests yet</p>
                                    <a href="{{ route('client.documents.create') }}"
                                        class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                                        Request Your First Document
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($documents->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $documents->links() }}
            </div>
        @endif
    </div>

    <div class="mt-6">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-4 py-3 border-b">
                <h5 class="text-lg font-semibold">Document Status Guide</h5>
            </div>
            <div class="p-4">
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <ul class="space-y-3">
                            <li class="flex items-center">
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mr-2">Pending</span>
                                <span class="text-sm text-gray-700">Your request has been submitted and is awaiting
                                    review by an admin</span>
                            </li>
                            <li class="flex items-center">
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2">Processing</span>
                                <span class="text-sm text-gray-700">An admin is currently reviewing your document</span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <ul class="space-y-3">
                            <li class="flex items-center">
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-2">Completed</span>
                                <span class="text-sm text-gray-700">Your document has been approved and is ready for
                                    download</span>
                            </li>
                            <li class="flex items-center">
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mr-2">Rejected</span>
                                <span class="text-sm text-gray-700">Your request has been rejected (see details for
                                    reason)</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>