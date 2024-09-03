@props(['disabled' => false])

<input 
    {!! $attributes->merge([
        'class' => 'border-none 
                    placeholder:text-gray-400 
                    placeholder:font-light 
                    placeholder:text-sm 
                    focus:border-slate-500 
                    focus:ring-slate-500 
                    rounded-xl 
                    shadow-sm ' . ($disabled ? 'bg-gray-200 cursor-not-allowed' : '')
    ]) !!}
    {{ $disabled ? 'disabled' : '' }}
>
