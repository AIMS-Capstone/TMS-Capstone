{{-- Used on Profile --}}
<div class="md:col-span-1 flex justify-between">
    <div class="px-4 sm:px-0">
        <h3 class="text-xl font-bold text-blue-900">{{ $title }}</h3>

        <p class="mt-1 text-sm text-zinc-500">
            {{ $description }}
        </p>
    </div>

    <div class="px-4 sm:px-0">
        {{ $aside ?? '' }}
    </div>
</div>
