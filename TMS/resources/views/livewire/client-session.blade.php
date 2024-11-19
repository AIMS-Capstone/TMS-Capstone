<x-action-section>
    <x-slot name="title">
        {{ __('Browser Sessions') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Manage and log out your active sessions on other browsers and devices.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm tracking-tight text-zinc-600">
            {{ __('If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.') }}
        </div>

        @if (isset($logoutSuccess))
            <div class="mt-4 text-sm text-green-600">
                {{ $logoutSuccess }}
            </div>
        @elseif (isset($logoutError))
            <div class="mt-4 text-sm text-red-600">
                {{ $logoutError }}
            </div>
        @endif

        @if (count($sessions) > 0)
           <div class="mt-5 space-y-6">
                @foreach ($sessions as $session)
                    <div class="flex items-center">
                        <div>
                            @if ($session->agentInfo->platform === 'Desktop')
                                <!-- Desktop Icon -->
                                <svg class="w-8 h-8 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M4 5h16v10H4V5zm16 12H4v2h16v-2z" />
                                </svg>
                            @elseif ($session->agentInfo->platform === 'Tablet')
                                <!-- Tablet Icon -->
                                <svg class="w-8 h-8 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2zm5 16a1 1 0 100-2 1 1 0 000 2z" />
                                </svg>
                            @else
                                <!-- Mobile Icon -->
                                <svg class="w-8 h-8 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M7 2h10a2 2 0 012 2v16a2 2 0 01-2 2H7a2 2 0 01-2-2V4a2 2 0 012-2zm5 18a1 1 0 100-2 1 1 0 000 2z" />
                                </svg>
                            @endif
                        </div>

                        <div class="ms-3">
                            <div class="text-sm text-gray-600">
                                {{ $session->agentInfo->platform }} - {{ $session->agentInfo->browser }}
                            </div>
                            <div class="text-sm text-gray-600">
                                {{ $session->agentInfo->ip_address }}
                                @if ($session->is_current_device)
                                    , <span class="text-green-500 font-semibold">{{ __('This device') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="flex items-center mt-5">
            <x-button wire:click="confirmLogout" wire:loading.attr="disabled">
                {{ __('Log Out Other Browser Sessions') }}
            </x-button>
        </div>

        <x-dialog-modal wire:model="confirmingLogout">
            <x-slot name="title">{{ __('Log Out Other Browser Sessions') }}</x-slot>

            <x-slot name="content">
                {{ __('Please enter your password to confirm you would like to log out of your other browser sessions across all of your devices.') }}
                <div class="mt-4" x-data="{}" x-on:confirming-logout-other-browser-sessions.window="setTimeout(() => $refs.password.focus(), 250)">
                    <x-input type="password" class="mt-1 block w-3/4" autocomplete="current-password" placeholder="{{ __('Password') }}" x-ref="password" wire:model="password" wire:keydown.enter="logoutOtherBrowserSessions" />
                    <x-input-error for="password" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingLogout')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-button class="ms-3" wire:click="logoutOtherBrowserSessions" wire:loading.attr="disabled">
                    {{ __('Log Out Other Browser Sessions') }}
                </x-button>
            </x-slot>
        </x-dialog-modal>
    </x-slot>
</x-action-section>
