@props(['active'])

@php
    $classes = $active ?? false ? 'block pl-3 pr-4 py-2 border-l-4 border-indigo-400 text-base font-medium text-white bg-gray-700 focus:outline-none focus:text-indigo-100 focus:bg-gray-900 focus:border-indigo-700 transition duration-150 ease-in-out' : 'block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-400 hover:text-gray-100 hover:bg-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-100 focus:bg-gray-700 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>