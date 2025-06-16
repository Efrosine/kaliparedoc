<x-layouts.app>
    <x-slot name="header">
        Activity Logs
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <form action="{{ route('logs.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>
                                <select name="user_id" id="user_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">All Users</option>
                                    @foreach($users as $id => $name)
                                        <option value="{{ $id }}" {{ request('user_id') == $id ? 'selected' : '' }}>
                                            {{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="action" class="block text-sm font-medium text-gray-700">Action</label>
                                <input type="text" name="action" id="action" value="{{ request('action') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="model_type" class="block text-sm font-medium text-gray-700">Entity
                                    Type</label>
                                <select name="model_type" id="model_type"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">All Types</option>
                                    @foreach($modelTypes as $type)
                                        <option value="{{ $type }}" {{ request('model_type') == $type ? 'selected' : '' }}>
                                            {{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Start
                                    Date</label>
                                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Filter
                                </button>
                                <a href="{{ route('logs.index') }}"
                                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring ring-blue-200 disabled:opacity-25 transition ease-in-out duration-150 ml-2">
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100 text-gray-700">
                                <tr>
                                    <th class="py-3 px-4 text-left text-xs font-medium uppercase tracking-wider">Time
                                    </th>
                                    <th class="py-3 px-4 text-left text-xs font-medium uppercase tracking-wider">User
                                    </th>
                                    <th class="py-3 px-4 text-left text-xs font-medium uppercase tracking-wider">Action
                                    </th>
                                    <th class="py-3 px-4 text-left text-xs font-medium uppercase tracking-wider">Entity
                                        Type</th>
                                    <th class="py-3 px-4 text-left text-xs font-medium uppercase tracking-wider">Entity
                                        ID</th>
                                    <th class="py-3 px-4 text-left text-xs font-medium uppercase tracking-wider">Details
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @forelse($logs as $log)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-2 px-4">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td class="py-2 px-4">{{ $log->user->name ?? 'Unknown' }}</td>
                                        <td class="py-2 px-4">{{ $log->action }}</td>
                                        <td class="py-2 px-4">{{ $log->model_type ?? '-' }}</td>
                                        <td class="py-2 px-4">{{ $log->model_id ?? '-' }}</td>
                                        <td class="py-2 px-4">
                                            <a href="{{ route('logs.show', $log->id) }}"
                                                class="text-blue-600 hover:text-blue-900">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-4 px-4 text-center text-gray-500">No logs found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $logs->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>