@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm auth-color justify-start items-start']) }}>
    {{ $value ?? $slot }}<span class="text-red-500 ml-1">*</span>
</label>