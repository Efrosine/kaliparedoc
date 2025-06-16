<x-layouts.app>
    <x-slot name="header">
        Log Detail
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('logs.index') }}" class="text-blue-600 hover:text-blue-800">
                    <i class="fa fa-arrow-left mr-1"></i> Back to Logs
                </a>
            </div>
            
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Log Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-600">Action</p>
                            <p class="font-medium">{{ $log->action }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Time</p>
                            <p class="font-medium">{{ $log->created_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">User</p>
                            <p class="font-medium">{{ $log->user->name ?? 'Unknown' }} ({{ $log->user->email ?? 'No email' }})</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Entity Type</p>
                            <p class="font-medium">{{ $log->model_type ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Entity ID</p>
                            <p class="font-medium">{{ $log->model_id ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    @if($relatedModel)
                        <div class="mb-6">
                            <h4 class="font-semibold mb-2">Related Entity</h4>
                            
                            <div class="bg-gray-50 p-4 rounded">
                                @if($log->model_type === 'Document')
                                    <p><strong>Document Type:</strong> {{ $relatedModel->documentType->name ?? 'N/A' }}</p>
                                    <p><strong>Status:</strong> {{ ucfirst($relatedModel->status) }}</p>
                                    <p><strong>Client:</strong> {{ $relatedModel->user->name ?? 'Unknown' }}</p>
                                    <p><strong>NIK:</strong> {{ $relatedModel->nik }}</p>
                                    <p><strong>KK:</strong> {{ $relatedModel->kk }}</p>
                                    <p><strong>Created:</strong> {{ $relatedModel->created_at->format('Y-m-d H:i:s') }}</p>
                                @elseif($log->model_type === 'Template' || $log->model_type === 'DocumentType' || $log->model_type === 'NumberFormat')
                                    <p><strong>Name:</strong> {{ $relatedModel->name ?? 'N/A' }}</p>
                                    <p><strong>Current Version:</strong> {{ $relatedModel->currentVersion->version ?? 'N/A' }}</p>
                                    <p><strong>Created:</strong> {{ $relatedModel->created_at->format('Y-m-d H:i:s') }}</p>
                                @elseif($log->model_type === 'User')
                                    <p><strong>Name:</strong> {{ $relatedModel->name }}</p>
                                    <p><strong>Email:</strong> {{ $relatedModel->email }}</p>
                                    <p><strong>Role:</strong> {{ ucfirst($relatedModel->role) }}</p>
                                @else
                                    <p class="text-gray-500">No detailed information available for this entity type.</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
