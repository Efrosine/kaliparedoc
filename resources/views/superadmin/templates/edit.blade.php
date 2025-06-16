<x-layouts.app>
    <x-slot name="header">
        Edit Template
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('templates.index') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Back to
                Templates</a>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">Edit Template for: {{ $template->documentType->name }}</h2>

            <form action="{{ route('templates.update', $template) }}" method="POST">
                @csrf
                @method('PUT')

                <x-wysiwyg-editor name="html_content" label="Template HTML Content" :value="$template->currentVersion ? $template->currentVersion->html_content : ''" />

                <div class="flex justify-between mt-6">
                    <div>
                        <a href="{{ route('templates.history', $template) }}"
                            class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                            View Version History
                        </a>
                        @if($template->currentVersion)
                            <a href="{{ route('templates.preview', $template->currentVersion) }}"
                                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 ml-2">
                                Preview Current Version
                            </a>
                        @endif
                    </div>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Update Template
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>