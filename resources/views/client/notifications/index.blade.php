<x-layouts.app>
    <x-slot name="header">
        Notifications
    </x-slot>

    <div class="mb-4 flex justify-between items-center">
        <div>
            @if($notifications->count() > 0)
                <form action="{{ route('client.notifications.mark-all-read') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 border border-indigo-600 text-indigo-600 rounded hover:bg-indigo-50 transition">Mark
                        All as Read</button>
                </form>
            @endif
        </div>
    </div>

    @include('components.alert')

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="bg-gray-50 px-4 py-3 border-b">
            <h5 class="text-lg font-semibold">Your Notifications</h5>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($notifications as $notification)
                <div class="p-4 {{ $notification->is_read ? '' : 'bg-blue-50' }}">
                    <div class="flex justify-between items-start">
                        <div>
                            @if(!$notification->is_read)
                                <span
                                    class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 mr-2">New</span>
                            @endif
                            <p class="mb-1">{{ $notification->message }}</p>
                            <p class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        @if(!$notification->is_read)
                            <form action="{{ route('client.notifications.mark-read', $notification) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Mark as
                                    read</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-10">
                    <p class="text-gray-500">No notifications yet</p>
                </div>
            @endforelse
        </div>
        @if($notifications->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>