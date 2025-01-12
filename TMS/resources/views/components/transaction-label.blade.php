@props(['value', 'required' => false])

<label {{ $attributes->merge(['class' => 'block font-bold text-sm auth-color mb-5']) }}> 
    {{ $value ?? $slot }} 
    @if($required)
        <span class="text-red-700">*</span>
    @endif
</label>