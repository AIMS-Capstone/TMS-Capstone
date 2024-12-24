<div {{ $attributes->merge(['class' => 'md:flex md:flex-col md:gap-6 px-12 py-4']) }}>
    <div class="mt-5 mb-20 md:mt-0">
        <form wire:submit="saveTransaction">
            <div class="px-4 py-5 bg-white sm:p-6 border-neutral-300 shadow {{ isset($actions) ? 'sm:rounded-t-lg sm:rounded-b-lg' : 'sm:rounded-lg' }}">
                <div>
                    <x-transaction-section-title>
                        <x-slot name="redirection">{{ $redirection }}</x-slot>
                        <x-slot name="description">{{ $description }}</x-slot>
                        <x-slot name="wildcard">{{$wildcard ?? ''}}</x-slot>
                    </x-transaction-section-title>
                    <div class="justify-end flex relative text-left mb-4">
                        {{ $options ?? '' }} <!-- This is where the dropdown will be rendered -->
                    </div>
                    <div class="border-2 rounded-t-md mt-6">
                        {{ $form }}
                    </div>
                </div>
                <!-- Render the after slot here -->
                <div class="mt-6">
                    {{ $after ?? '' }}
                </div>
                @if (isset($actions))
                    <div class="flex items-center justify-between px-4 py-3 bg-white text-end sm:px-6  sm:rounded-bl-md sm:rounded-br-md">
                        {{ $actions }}
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>
