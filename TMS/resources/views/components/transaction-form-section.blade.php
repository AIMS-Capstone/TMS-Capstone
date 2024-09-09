
<div {{ $attributes->merge(['class' => 'md:flex md:flex-col md:gap-6 px-12 py-4']) }}>


    <div class="mt-5 md:mt-0">
        
       <form wire:submit="saveTransaction">
            <div class="px-4 py-5 bg-white sm:p-6  shadow {{ isset($actions) ? 'sm:rounded-tl-md sm:rounded-tr-md' : 'sm:rounded-md' }}">
                <div>
                    <x-transaction-section-title>
                        <x-slot name="redirection">{{ $redirection }}</x-slot>
                        <x-slot name="description">{{ $description }}</x-slot>
                    </x-transaction-section-title>
                    <div class="border-2 rounded-t-md mt-6">
                    {{ $form }}
                    </div>
                </div>
            </div>

            @if (isset($actions))
                <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-end sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                    {{ $actions }}
                </div>
            @endif
        </form>
    </div>
</div>
