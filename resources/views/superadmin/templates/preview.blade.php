<x-layouts.app>
    <x-slot name="header">
        Template Preview
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex justify-between">
            <a href="{{ route('templates.history', $template) }}" class="text-indigo-600 hover:text-indigo-900">&larr;
                Back to Version History</a>
            <span class="text-gray-500">Previewing version {{ $version->version }}</span>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">Document Preview with Sample Data</h2>

            <div class="border p-6 rounded">
                {!! $html !!}
            </div>

            <div class="mt-6">
                <p class="text-gray-500 text-sm">
                    Note: This is a preview with sample data. Actual documents may look different based on real user
                    data.
                </p>
            </div>
        </div>
    </div>
</x-layouts.app>