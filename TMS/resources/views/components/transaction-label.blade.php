@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-sm auth-color mb-5']) }}>
    {{ $value ?? $slot }}
</label>