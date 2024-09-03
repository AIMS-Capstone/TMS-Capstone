@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm auth-color']) }}>
    {{ $value ?? $slot }}
</label>