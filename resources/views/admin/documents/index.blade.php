<x-layouts.app>
    <x-slot name="header">
        Document Requests
    </x-slot>
    <div class="py-4">
        <div class="mb-4 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Document Requests</h2>
            <div>
                <form action="{{ route('admin.documents.index') }}" method="GET" class="flex items-center">
                    <div class="mr-2 flex" style="width: 250px;">
                        <select name="status" class="w-full rounded-l-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="" {{ request()->missing('status') ? 'selected' : '' }}>All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        <button class="rounded-r-md border border-l-0 border-gray-300 bg-gray-50 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" type="submit">Filter</button>
                    </div>
                    
                    <div class="flex items-center mr-3">
                        <div class="relative inline-block w-10 mr-2 align-middle select-none">
                            <input type="checkbox" id="showOverdue" name="overdue" value="1" 
                                {{ request()->has('overdue') ? 'checked' : '' }} onchange="this.form.submit()"
                                class="checked:bg-indigo-500 outline-none focus:outline-none right-4 checked:right-0 duration-200 ease-in absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                            <label for="showOverdue" class="block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                        </div>
                        <label class="text-sm text-gray-700" for="showOverdue">Show Overdue Only</label>
                    </div>
                </form>
            </div>
        </div>

        @include('components.alert')
        
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-4 py-3 border-b">
                @if(request()->has('overdue'))
                    <h5 class="text-lg font-semibold">Overdue Document Requests (3+ days old)</h5>
                @elseif(request('status'))
                    <h5 class="text-lg font-semibold">{{ ucfirst(request('status')) }} Document Requests</h5>
                @else
                    <h5 class="text-lg font-semibold">All Document Requests</h5>
                @endif
            </div>
            <div class="p-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIK</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($documents as $document)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $document->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $document->documentType->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $document->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $document->nik }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClass = match($document->status) {
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'processing' => 'bg-blue-100 text-blue-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ ucfirst($document->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $document->created_at->format('d M Y') }}
                                    @if($document->created_at <= now()->subDays(3) && in_array($document->status, ['pending', 'processing']))
                                        <span class="ml-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Overdue</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.documents.show', $document) }}" class="px-3 py-1 bg-indigo-600 text-white rounded text-xs hover:bg-indigo-700">
                                        Process
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-500">No document requests found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-700">
                        Showing {{ $documents->firstItem() ?? 0 }} to {{ $documents->lastItem() ?? 0 }} of {{ $documents->total() }} entries
                    </div>
                    <div>
                        {{ $documents->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>