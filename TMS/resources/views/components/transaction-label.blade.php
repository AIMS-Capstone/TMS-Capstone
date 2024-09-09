@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm auth-color mb-5']) }}>
    {{ $value ?? $slot }}
</label>