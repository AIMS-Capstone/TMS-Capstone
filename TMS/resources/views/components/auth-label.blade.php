@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm auth-color']) }}>
    {{ $value ?? $slot }}<span class="text-red-500 ml-1">*</span>
</label>