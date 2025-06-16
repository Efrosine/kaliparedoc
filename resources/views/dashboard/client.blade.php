<x-layouts.app>
    <x-slot name="header">
        Client Dashboard
    </x-slot>

    <div class="mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Request New Document -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Request New Document</h3>
                    <p class="text-gray-600 mb-4">Submit a request for a new official document.</p>
                    <a href="{{ route('client.documents.create') }}"
                        class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        New Request
                    </a>
                </div>
            </div>

            <!-- My Documents -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">My Documents</h3>
                    <p class="text-gray-600 mb-4">View all your document requests and their status.</p>
                    <a href="{{ route('client.documents.index') }}"
                        class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        View Documents
                    </a>
                </div>
            </div>

            <!-- Notifications -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">
                        Notifications
                        @if(isset($unreadCount) && $unreadCount > 0)
                            <span
                                class="ml-2 px-2 py-1 bg-red-500 text-white rounded-full text-xs">{{ $unreadCount }}</span>
                        @endif
                    </h3>
                    <p class="text-gray-600 mb-4">View your document status updates and notifications.</p>
                    <a href="#" class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        View Notifications
                    </a>
                </div>
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
                                    <a href="{{ route('client.documents.show', $document) }}"
                                        class="text-blue-600 hover:text-blue-800">
                                        View
                                    </a>
                                    @if($document->status === 'completed')
                                        <a href="{{ route('client.documents.download', $document) }}"
                                            class="ml-3 text-green-600 hover:text-green-800">
                                            Download
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    <a href="{{ route('client.documents.index') }}" class="text-blue-600 hover:text-blue-800">View all
                        documents →</a>
                </div>
            @else
                <p>No recent document requests.</p>
            @endif
        </div>
    </div>
</x-layouts.app>