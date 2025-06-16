<x-layouts.app>
    <x-slot name="header">
        Notifications
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Your Notifications</h3>

                        @if($notifications->isNotEmpty())
                            <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-sm text-blue-600 hover:text-blue-800">
                                    Mark all as read
                                </button>
                            </form>
                        @endif
                    </div>

                    @if($notifications->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            <p>You have no notifications.</p>
                        </div>
                    @else
                        <ul class="divide-y">
                            @foreach($notifications as $notification)
                                <li class="py-4 {{ $notification->is_read ? 'opacity-75' : 'bg-blue-50' }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="text-sm mb-1">
                                                {{ $notification->message }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </div>

                                        @if(!$notification->is_read)
                                            <form action="{{ route('admin.notifications.mark-read', $notification) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="text-xs text-gray-600 hover:text-gray-900 ml-4">
                                                    Mark as read
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <div class="mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>