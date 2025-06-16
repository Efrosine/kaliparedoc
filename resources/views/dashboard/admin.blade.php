<x-layouts.app>
    <x-slot name="header">
        Admin Dashboard
    </x-slot>

    <div class="mb-8">
        <h2 class="text-lg font-semibold mb-4">Document Requests Overview</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <h3 class="text-3xl font-bold mb-1">{{ $pendingCount ?? 0 }}</h3>
                <p class="text-gray-600">Pending Requests</p>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <h3 class="text-3xl font-bold mb-1">{{ $processingCount ?? 0 }}</h3>
                <p class="text-gray-600">In Progress</p>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <h3 class="text-3xl font-bold mb-1">{{ $completedCount ?? 0 }}</h3>
                <p class="text-gray-600">Completed</p>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <h3 class="text-3xl font-bold mb-1">{{ $rejectedCount ?? 0 }}</h3>
                <p class="text-gray-600">Rejected</p>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <h2 class="text-lg font-semibold mb-4">Recent Document Requests</h2>
            @if(isset($recentDocuments) && count($recentDocuments) > 0)
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="py-2 px-3 bg-gray-100 font-semibold text-sm text-gray-600 border-b">NIK</th>
                            <th class="py-2 px-3 bg-gray-100 font-semibold text-sm text-gray-600 border-b">Document Type
                            </th>
                            <th class="py-2 px-3 bg-gray-100 font-semibold text-sm text-gray-600 border-b">Status</th>
                            <th class="py-2 px-3 bg-gray-100 font-semibold text-sm text-gray-600 border-b">Requested Date
                            </th>
                            <th class="py-2 px-3 bg-gray-100 font-semibold text-sm text-gray-600 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentDocuments ?? [] as $document)
                            <tr>
                                <td class="py-2 px-3 border-b">{{ $document->nik }}</td>
                                <td class="py-2 px-3 border-b">{{ $document->documentType->name }}</td>
                                <td class="py-2 px-3 border-b">
                                    <span
                                        class="px-2 py-1 rounded text-xs 
                                                            {{ $document->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                            {{ $document->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                                            {{ $document->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                                            {{ $document->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($document->status) }}
                                    </span>
                                </td>
                                <td class="py-2 px-3 border-b">{{ $document->created_at->format('M d, Y') }}</td>
                                <td class="py-2 px-3 border-b">
                                    <a href="{{ route('admin.documents.show', $document) }}"
                                        class="text-blue-600 hover:text-blue-800">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    <a href="{{ route('admin.documents.index') }}" class="text-blue-600 hover:text-blue-800">View all
                        documents →</a>
                </div>
            @else
                <p>No recent document requests.</p>
            @endif
        </div>
    </div>

    @if(isset($overdueDocuments) && count($overdueDocuments) > 0)
        <div class="mt-6 bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h2 class="text-lg font-semibold mb-4 text-red-600">Overdue Requests (Pending > 3 days)</h2>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="py-2 px-3 bg-gray-100 font-semibold text-sm text-gray-600 border-b">NIK</th>
                            <th class="py-2 px-3 bg-gray-100 font-semibold text-sm text-gray-600 border-b">Document Type
                            </th>
                            <th class="py-2 px-3 bg-gray-100 font-semibold text-sm text-gray-600 border-b">Days Pending</th>
                            <th class="py-2 px-3 bg-gray-100 font-semibold text-sm text-gray-600 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($overdueDocuments as $document)
                            <tr>
                                <td class="py-2 px-3 border-b">{{ $document->nik }}</td>
                                <td class="py-2 px-3 border-b">{{ $document->documentType->name }}</td>
                                <td class="py-2 px-3 border-b">{{ now()->diffInDays($document->created_at) }}</td>
                                <td class="py-2 px-3 border-b">
                                    <a href="{{ route('admin.documents.show', $document) }}"
                                        class="text-blue-600 hover:text-blue-800">
                                        Process Now
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-layouts.app>