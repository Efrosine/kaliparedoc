<x-layouts.app>
    <x-slot name="header">
        Edit User
    </x-slot>

    <div class="w-full md:max-w-xl mx-auto bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <x-form-input label="Name" name="name" :value="$user->name ?? ''" required />
            </div>

            <div class="mb-6">
                <x-form-input label="Email" name="email" type="email" :value="$user->email ?? ''" required />
            </div>

            <div class="mb-6">
                <x-form-input label="Password (leave blank to keep current)" name="password" type="password" />
            </div>

            <div class="mb-6">
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <select id="role" name="role"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="super_admin" {{ ($user->role ?? '') == 'super_admin' ? 'selected' : '' }}>Super Admin
                    </option>
                    <option value="admin" {{ ($user->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end">
                <a href="{{ route('users.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Update User
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>