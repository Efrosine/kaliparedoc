<x-layouts.app>
    <x-slot name="header">
        Request Document
    </x-slot>

    <div class="mb-4 flex justify-between items-center">
        <a href="{{ route('client.documents.index') }}"
            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">Back to My Documents</a>
    </div>

    @include('components.alert')

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="bg-gray-50 px-4 py-3 border-b">
            <h5 class="text-lg font-semibold">Document Request Form</h5>
        </div>
        <div class="p-4">
            <form action="{{ route('client.documents.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <div class="md:w-1/2">
                        <label for="document_type_id" class="block text-sm font-medium text-gray-700 mb-1">Document
                            Type</label>
                        <select
                            class="w-full rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50 focus:border-indigo-500 {{ $errors->has('document_type_id') ? 'border-red-500' : 'border-gray-300' }}"
                            id="document_type_id" name="document_type_id" required>
                            <option value="">-- Select Document Type --</option>
                            @foreach($documentTypes as $type)
                                <option value="{{ $type->id }}" {{ old('document_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('document_type_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <h5 class="mt-8 mb-4 text-lg font-medium text-gray-700">Identification Information</h5>
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">NIK (16 digits)</label>
                        <input type="text"
                            class="w-full rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50 focus:border-indigo-500 {{ $errors->has('nik') ? 'border-red-500' : 'border-gray-300' }}"
                            id="nik" name="nik" value="{{ old('nik') }}" required maxlength="16" pattern="[0-9]{16}">
                        @error('nik')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Format: 16 digits number</p>
                    </div>
                    <div>
                        <label for="kk" class="block text-sm font-medium text-gray-700 mb-1">Family Card Number
                            (KK)</label>
                        <input type="text"
                            class="w-full rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50 focus:border-indigo-500 {{ $errors->has('kk') ? 'border-red-500' : 'border-gray-300' }}"
                            id="kk" name="kk" value="{{ old('kk') }}" required maxlength="16" pattern="[0-9]{16}">
                        @error('kk')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Format: 16 digits number</p>
                    </div>
                </div>

                <h5 class="mt-8 mb-4 text-lg font-medium text-gray-700">Personal Information</h5>
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text"
                            class="w-full rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50 focus:border-indigo-500 {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }}"
                            id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                        <select
                            class="w-full rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50 focus:border-indigo-500 {{ $errors->has('gender') ? 'border-red-500' : 'border-gray-300' }}"
                            id="gender" name="gender">
                            <option value="">-- Select Gender --</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="birth_place" class="block text-sm font-medium text-gray-700 mb-1">Place of
                            Birth</label>
                        <input type="text"
                            class="w-full rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50 focus:border-indigo-500 {{ $errors->has('birth_place') ? 'border-red-500' : 'border-gray-300' }}"
                            id="birth_place" name="birth_place" value="{{ old('birth_place') }}">
                        @error('birth_place')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Date of
                            Birth</label>
                        <input type="date"
                            class="w-full rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50 focus:border-indigo-500 {{ $errors->has('birth_date') ? 'border-red-500' : 'border-gray-300' }}"
                            id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                        @error('birth_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Full Address</label>
                    <textarea
                        class="w-full rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50 focus:border-indigo-500 {{ $errors->has('address') ? 'border-red-500' : 'border-gray-300' }}"
                        id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="religion" class="block text-sm font-medium text-gray-700 mb-1">Religion</label>
                        <input type="text"
                            class="w-full rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50 focus:border-indigo-500 {{ $errors->has('religion') ? 'border-red-500' : 'border-gray-300' }}"
                            id="religion" name="religion" value="{{ old('religion') }}">
                        @error('religion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="occupation" class="block text-sm font-medium text-gray-700 mb-1">Occupation</label>
                        <input type="text"
                            class="w-full rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50 focus:border-indigo-500 {{ $errors->has('occupation') ? 'border-red-500' : 'border-gray-300' }}"
                            id="occupation" name="occupation" value="{{ old('occupation') }}">
                        @error('occupation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="marital_status" class="block text-sm font-medium text-gray-700 mb-1">Marital
                        Status</label>
                    <select
                        class="w-full rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50 focus:border-indigo-500 {{ $errors->has('marital_status') ? 'border-red-500' : 'border-gray-300' }}"
                        id="marital_status" name="marital_status">
                        <option value="">-- Select Marital Status --</option>
                        <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Single</option>
                        <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Married
                        </option>
                        <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced
                        </option>
                        <option value="widowed" {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>Widowed
                        </option>
                    </select>
                    @error('marital_status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-8">
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">Submit
                        Request</button>
                    <a href="{{ route('client.dashboard') }}"
                        class="ml-4 px-4 py-2 text-indigo-600 hover:text-indigo-900">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>