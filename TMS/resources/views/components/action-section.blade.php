<div {{ $attributes->merge(['class' => 'flex flex-row justify-between pl-4']) }}>
    <x-section-title>
        <x-slot name="title">{{ $title }}</x-slot>
        <x-slot name="description">{{ $description }}</x-slot>
    </x-section-title>

    <div class="max-w-3xl mx-auto rounded-lg shadow-sm ml-14">
        <div class="p-10 bg-white sm:rounded-lg">
            {{ $content }}
        </div>
    </div>
</div>
