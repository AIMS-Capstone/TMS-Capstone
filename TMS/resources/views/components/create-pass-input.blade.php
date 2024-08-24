@props(['disabled' => false])

<input 
    {{ $disabled ? 'disabled' : '' }} 
    {!! $attributes->merge(['class' => 'w-full border-gray-300 placeholder:text-gray-400 placeholder:font-light placeholder:text-sm focus:border-slate-500 focus:ring-slate-500 rounded-xl shadow-sm']) !!}
>