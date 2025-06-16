<x-layouts.app>
    <x-slot name="header">
        Document Preview
    </x-slot>

    <div class="py-4">
        <div class="mb-4 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Document Preview</h2>
            <div>
                <a href="{{ route('admin.documents.show', $document) }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                    Back to Document Details
                </a>
                <a href="{{ route('admin.documents.approve', $document) }}"
                    class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition ml-2"
                    onclick="event.preventDefault(); document.getElementById('approve-form').submit();">
                    Approve Document
                </a>
                <form id="approve-form" action="{{ route('admin.documents.approve', $document) }}" method="POST"
                    class="hidden">
                    @csrf
                </form>
            </div>
        </div>

        @include('components.alert')

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-4 py-3 border-b">
                <h5 class="text-lg font-semibold">Document Preview</h5>
                <p class="text-sm text-gray-500 mt-1">This is how the document will look after approval. Document number
                    will be
                    generated upon approval.</p>
            </div>
            <div class="p-4">
                <div class="border p-4 bg-white" style="min-height: 700px;">
                    {!! $preview !!}
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>