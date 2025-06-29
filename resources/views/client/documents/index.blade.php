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
</x-layouts.app>