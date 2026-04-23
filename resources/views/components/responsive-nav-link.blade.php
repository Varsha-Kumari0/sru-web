@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full border-l-4 border-[#0f5ab8] bg-blue-50 py-2 ps-3 pe-4 text-start text-base font-medium text-[#0f5ab8] focus:border-[#0b4e9f] focus:bg-blue-100 focus:text-[#0b4e9f] focus:outline-none transition duration-150 ease-in-out'
            : 'block w-full border-l-4 border-transparent py-2 ps-3 pe-4 text-start text-base font-medium text-slate-600 hover:border-slate-300 hover:bg-slate-50 hover:text-gray-800 focus:border-slate-300 focus:bg-slate-50 focus:text-gray-800 focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
