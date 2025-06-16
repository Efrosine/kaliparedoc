<x-layouts.guest>
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold">Village Document Management System</h1>
        <p class="text-gray-600">Please login to access the system</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <x-form-input label="Email" name="email" type="email" required />

        <!-- Password -->
        <x-form-input label="Password" name="password" type="password" required />

        <div class="flex items-center justify-end mt-6">
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Log in
            </button>
        </div>
    </form>
</x-layouts.guest>