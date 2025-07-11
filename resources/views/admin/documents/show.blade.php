<x-layouts.app>
    <x-slot name="header">
        Document Request Details
    </x-slot>

    <div class="py-4">
        <div class="mb-4 flex justify-between items-center">
            <a href="{{ route('admin.documents.index') }}"
                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">Back to Documents</a>
            @if($document->status === 'completed')
                <a href="{{ route('admin.documents.download', $document) }}"
                    class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition ml-2">
                    <i class="fas fa-download mr-1"></i> Download Document
                </a>
            @endif
        </div>

        @include('components.alert')

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2">
                <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                    <div class="bg-gray-50 px-4 py-3 border-b flex justify-between items-center">
                        <h5 class="text-lg font-semibold">Admin Actions</h5>
                        @if($document->status === 'pending')
                            <div class="flex gap-2">
                                <form action="{{ route('admin.documents.approve', $document) }}" method="POST"
                                    onsubmit="return confirm('Approve this document?');">
                                    @csrf
                                    <button type="submit"
                                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Approve</button>
                                </form>
                                <a href="{{ route('admin.documents.preview', $document) }}"
                                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Preview & Edit
                                    Data</a>
                                <form action="{{ route('admin.documents.reject', $document) }}" method="POST"
                                    onsubmit="return confirm('Reject this document?');">
                                    @csrf
                                    <input type="text" name="reason" placeholder="Rejection reason" required
                                        class="px-2 py-1 border rounded mr-2">
                                    <button type="submit"
                                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Reject</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                    <div class="bg-gray-50 px-4 py-3 border-b">
                        <h5 class="text-lg font-semibold">Document Information</h5>
                        @php
                            $statusClass = match ($document->status) {
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
                    </div>
                    <div class="p-4">
                        <table class="min-w-full divide-y divide-gray-200">
                            <tr class="bg-white">
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 w-52">Document Type
                                </th>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $document->documentType->name }}</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 w-52">Request Date</th>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    {{ $document->created_at->format('d F Y, H:i') }}
                                </td>
                            </tr>
                            @if($document->number)
                                <tr class="bg-white">
                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 w-52">Document Number
                                    </th>
                                    <td class="px-4 py-3 text-sm text-gray-900 font-bold">{{ $document->number }}</td>
                                </tr>
                            @endif
                            <tr class="{{ $document->number ? 'bg-gray-50' : 'bg-white' }}">
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 w-52">NIK</th>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $document->nik }}</td>
                            </tr>
                            <tr class="{{ $document->number ? 'bg-white' : 'bg-gray-50' }}">
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 w-52">Family Card
                                    Number (KK)</th>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $document->kk }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b">
                        <h5 class="text-lg font-semibold">Personal Information</h5>
                    </div>
                    <div class="p-4">
                        <table class="min-w-full divide-y divide-gray-200">
                            @foreach($document->data_json as $key => $value)
                                @if(!in_array($key, ['_token', 'document_type_id', 'nik', 'kk']))
                                    <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 w-52">
                                            {{ ucwords(str_replace('_', ' ', $key)) }}
                                        </th>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            @if($key === 'birth_date' && !empty($value))
                                                {{ \Carbon\Carbon::parse($value)->format('d F Y') }}
                                            @elseif($key === 'marital_status' && !empty($value))
                                                {{ ucfirst($value) }}
                                            @elseif($key === 'gender' && !empty($value))
                                                {{ ucfirst($value) }}
                                            @else
                                                {{ $value }}
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>

            <div class="md:col-span-1">
                <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                    <div class="bg-gray-50 px-4 py-3 border-b">
                        <h5 class="text-lg font-semibold">Status Timeline</h5>
                    </div>
                    <div class="p-4">
                        <ul class="relative border-l border-gray-200 ml-3">
                            <li class="mb-6 ml-6">
                                <span
                                    class="absolute flex items-center justify-center w-6 h-6 bg-green-100 rounded-full -left-3 ring-8 ring-white">
                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                </span>
                                <h5 class="font-semibold text-gray-900">Document Requested</h5>
                                <p class="text-sm text-gray-500">{{ $document->created_at->format('d F Y, H:i') }}</p>
                            </li>

                            @if($document->status !== 'pending')
                                @php
                                    $timelineBgColor = match ($document->status) {
                                        'processing' => 'bg-blue-100',
                                        'completed' => 'bg-green-100',
                                        'rejected' => 'bg-red-100',
                                        default => 'bg-gray-100'
                                    };
                                    $timelineDotColor = match ($document->status) {
                                        'processing' => 'bg-blue-500',
                                        'completed' => 'bg-green-500',
                                        'rejected' => 'bg-red-500',
                                        default => 'bg-gray-500'
                                    };
                                @endphp
                                <li class="mb-6 ml-6">
                                    <span
                                        class="absolute flex items-center justify-center w-6 h-6 {{ $timelineBgColor }} rounded-full -left-3 ring-8 ring-white">
                                        <div class="w-3 h-3 {{ $timelineDotColor }} rounded-full"></div>
                                    </span>
                                    <h5 class="font-semibold text-gray-900">
                                        {{ $document->status === 'processing' ? 'Processing Started' : ($document->status === 'completed' ? 'Completed' : 'Rejected') }}
                                    </h5>
                                    <p class="text-sm text-gray-500">{{ $document->updated_at->format('d F Y, H:i') }}</p>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>