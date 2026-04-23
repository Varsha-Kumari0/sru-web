@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center border-b-2 border-[#0f5ab8] px-1 pt-1 text-sm font-medium leading-5 text-gray-800 focus:border-[#0b4e9f] focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium leading-5 text-slate-500 hover:border-slate-300 hover:text-gray-800 focus:border-slate-300 focus:text-gray-800 focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
