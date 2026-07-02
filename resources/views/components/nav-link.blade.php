@props(['active'])

@php
    $classes = ($active ?? false)
        ? 'flex items-center w-full px-4 py-2.5 rounded-lg bg-[#152F79] text-white font-semibold'
        : 'flex items-center w-full px-4 py-2.5 rounded-lg text-slate-600 hover:bg-[#88A4F4]/20 hover:text-black transition duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>