<x-layouts.app>
    <x-slot name="header">
        Super Admin Dashboard
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Users Card -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">User Management</h3>
                <p class="text-gray-600 mb-4">Manage system users including admins and clients.</p>
                <a href="{{ route('users.index') }}"
                    class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Manage Users
                </a>
            </div>
        </div>

        <!-- Document Types Card -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Document Types</h3>
                <p class="text-gray-600 mb-4">Configure document types available in the system.</p>
                <a href="{{ route('document-types.index') }}"
                    class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Manage Document Types
                </a>
            </div>
        </div>

        <!-- Templates Card -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Templates</h3>
                <p class="text-gray-600 mb-4">Configure document templates with WYSIWYG editor.</p>
                <a href="{{ route('templates.index') }}"
                    class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Manage Templates
                </a>
            </div>
        </div>

        <!-- Number Formats Card -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Number Formats</h3>
                <p class="text-gray-600 mb-4">Configure document numbering formats.</p>
                <a href="{{ route('number-formats.index') }}"
                    class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Manage Number Formats
                </a>
            </div>
        </div>

        <!-- Activity Logs Card -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Activity Logs</h3>
                <p class="text-gray-600 mb-4">View system activity logs and audit trails.</p>
                <a href="{{ route('logs.index') }}"
                    class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    View Logs
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>