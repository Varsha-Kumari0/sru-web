<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center rounded-full border border-slate-300 bg-white px-5 py-2.5 text-xs font-semibold uppercase tracking-widest text-gray-800 shadow-sm transition duration-150 ease-in-out hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#0f5ab8] focus:ring-offset-2 disabled:opacity-25']) }}>
    {{ $slot }}
</button>
