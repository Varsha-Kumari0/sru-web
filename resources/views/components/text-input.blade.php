@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-xl border border-slate-300 bg-white text-gray-800 placeholder:text-slate-400 shadow-sm transition focus:border-[#0f5ab8] focus:ring-[#0f5ab8] disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400']) }}>
