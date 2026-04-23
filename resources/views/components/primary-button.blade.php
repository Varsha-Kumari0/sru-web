<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-full border border-transparent bg-[#0f5ab8] px-5 py-2.5 text-xs font-semibold uppercase tracking-widest text-white shadow-md shadow-blue-200 transition duration-150 ease-in-out hover:bg-[#0b4e9f] focus:outline-none focus:ring-2 focus:ring-[#0f5ab8] focus:ring-offset-2 active:bg-[#0a4386]']) }}>
    {{ $slot }}
</button>
