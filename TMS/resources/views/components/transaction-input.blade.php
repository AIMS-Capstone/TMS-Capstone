@props(['disabled' => false])

<input 
    {!! $attributes->merge([
        'class' => '
        
       
                    placeholder:text-gray-400 
                    placeholder:font-light 
                    placeholder:text-sm 
                    focus:outline-none
                    focus:ring-0
                    focus:border-blue-900 
                    focus:ring-blue-900 
                    p-0
                    border-0
                    border-b
                    border-b-gray-200
                     ' . ($disabled ? 'bg-gray-200 cursor-not-allowed' : '')
    ]) !!}
    {{ $disabled ? 'disabled' : '' }}
>