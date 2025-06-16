<x-layouts.app>
    <x-slot name="header">
        Edit Number Format
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('number-formats.index') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Back to
                Number Formats</a>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">Edit Number Format for: {{ $numberFormat->documentType->name }}</h2>

            <div class="mb-4">
                <p>Document numbering format uses the following placeholders:</p>
                <ul class="list-disc list-inside ml-4">
                    <li><code>@{{village_code}}</code> - Village code (e.g., VILLAGE123)</li>
                    <li><code>@{{type}}</code> - Document type name</li>
                    <li><code>@{{number}}</code> - Sequential number (resets monthly/yearly)</li>
                    <li><code>@{{month}}</code> - Month in MM format</li>
                    <li><code>@{{year}}</code> - Year in YYYY format</li>
                </ul>
            </div>

            <form action="{{ route('number-formats.update', $numberFormat) }}" method="POST">
                @csrf
                @method('PUT')

                <x-form-input label="Format String" name="format_string" :value="$numberFormat->format_string"
                    required="true" />

                <div class="flex justify-between mt-6">
                    <a href="{{ route('number-formats.history', $numberFormat) }}"
                        class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                        View Version History
                    </a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Update Number Format
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>