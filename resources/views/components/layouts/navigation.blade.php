<nav x-data="{ open: false }" class="bg-gray-800">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="text-white font-bold text-xl">
                        Village Docs
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @auth
                        @if (auth()->user()->isSuperAdmin())
                            <x-nav-link :href="route('superadmin.dashboard')"
                                :active="request()->routeIs('superadmin.dashboard')">
                                Dashboard
                            </x-nav-link>
                            <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                                User Management
                            </x-nav-link>
                            <x-nav-link :href="route('document-types.index')" :active="request()->routeIs('document-types.*')">
                                Document Types
                            </x-nav-link>
                            <x-nav-link :href="route('templates.index')" :active="request()->routeIs('templates.*')">
                                Templates
                            </x-nav-link>
                            <x-nav-link :href="route('number-formats.index')" :active="request()->routeIs('number-formats.*')">
                                Number Formats
                            </x-nav-link>
                            <x-nav-link :href="route('logs.index')" :active="request()->routeIs('logs.*')">
                                Activity Logs
                            </x-nav-link>
                        @elseif (auth()->user()->isAdmin())
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                Dashboard
                            </x-nav-link>
                            <x-nav-link :href="route('admin.documents.index')"
                                :active="request()->routeIs('admin.documents.*')">
                                Documents
                            </x-nav-link>
                        @else
                            <x-nav-link :href="route('client.dashboard')" :active="request()->routeIs('client.dashboard')">
                                Dashboard
                            </x-nav-link>
                            <x-nav-link :href="route('client.documents.create')"
                                :active="request()->routeIs('client.documents.create')">
                                Request Document
                            </x-nav-link>
                            <x-nav-link :href="route('client.documents.index')"
                                :active="request()->routeIs('client.documents.index')">
                                My Documents
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    <!-- Notifications -->
                    <div class="ml-3 relative">
                        @php
                            $unreadCount = \App\Services\NotificationService::getUnreadCount(auth()->id());
                            $notificationRoute = '';
                            if (auth()->user()->isSuperAdmin()) {
                                $notificationRoute = '#';
                            } elseif (auth()->user()->isAdmin()) {
                                $notificationRoute = route('admin.notifications.index');
                            } else {
                                $notificationRoute = route('client.notifications.index');
                            }
                        @endphp

                        <a href="{{ $notificationRoute }}" class="text-gray-300 hover:text-white p-1 rounded-full relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if($unreadCount > 0)
                                <span
                                    class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">{{ $unreadCount }}</span>
                            @endif
                        </a>
                    </div>

                    <div class="ml-3 relative">
                        <div class="text-white">
                            {{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="ml-4 text-sm text-gray-300 hover:text-white">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-300 hover:text-white">Login</a>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <!-- Responsive Navigation Links -->
        <div class="pt-2 pb-3 space-y-1">
            @auth
                @if (auth()->user()->isSuperAdmin())
                    <x-responsive-nav-link :href="route('superadmin.dashboard')"
                        :active="request()->routeIs('superadmin.dashboard')">
                        Dashboard
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                        User Management
                    </x-responsive-nav-link>
                    <!-- More responsive links for SuperAdmin -->
                @elseif (auth()->user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        Dashboard
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.documents.index')"
                        :active="request()->routeIs('admin.documents.*')">
                        Documents
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="route('client.dashboard')" :active="request()->routeIs('client.dashboard')">
                        Dashboard
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('client.documents.create')"
                        :active="request()->routeIs('client.documents.create')">
                        Request Document
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('client.documents.index')"
                        :active="request()->routeIs('client.documents.index')">
                        My Documents
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-300">{{ auth()->user()->name }}</div>
                    <div class="font-medium text-sm text-gray-400">{{ auth()->user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full block pl-3 pr-4 py-2 text-left text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700">
                            Logout
                        </button>
                    </form>
                </div>
            @else
                <div class="px-4 py-2">
                    <a href="{{ route('login') }}" class="text-gray-300 hover:text-white">Login</a>
                </div>
            @endauth
        </div>
    </div>
</nav>