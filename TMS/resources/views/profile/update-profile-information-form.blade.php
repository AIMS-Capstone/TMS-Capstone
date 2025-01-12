<div submit="updateProfileInformation">

    <div name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" id="photo" class="hidden"
                            wire:model.live="photo"
                            x-ref="photo"
                            x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-label for="photo" value="{{ __('Photo') }}" />

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-secondary-button class="mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Select A New Photo') }}
                </x-secondary-button>

                @if ($this->user->profile_photo_path)
                    <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Remove Photo') }}
                    </x-secondary-button>
                @endif

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <div class="ml-4 flex flex-row justify-between">
            <div>
                <h2 class="text-xl font-bold text-blue-900">Profile Information</h2>
                <p class="text-zinc-500 text-sm mt-1">Update your account’s profile information and email address.</p>
            </div>
            <div class="max-w-3xl mx-auto rounded-lg shadow-sm bg-white ml-14 p-4">
                <div class="gap-6 p-6 text-zinc-700">
                    <div class="mb-5 flex justify-between space-x-4 items-start">
                        <div class="w-full">
                            <label class="block font-bold text-zinc-700">First Name</label>
                            <input type="text" class="mt-1 text-sm truncate w-full border border-gray-300 rounded-xl shadow-sm px-3 py-2 bg-white" 
                                value="{{ $this->user->first_name ?? 'PUP' }}" disabled>
                        </div>
                        <div class="w-full">
                            <label class="block font-bold text-zinc-700">Middle Name</label>
                            <input type="text" class="mt-1 text-sm w-full border border-gray-300 rounded-xl shadow-sm px-3 py-2 bg-white" 
                                value="{{ $this->user->middle_name ?? ' ' }}" disabled>
                        </div>
                    </div>

                    <!-- Classification Field -->
                    <div class="mb-5 flex justify-between space-x-4 items-start">
                        <div class="w-2/3">
                            <label class="block font-bold text-zinc-700">Last Name</label>
                            <input type="text" class="mt-1 text-sm w-full border border-gray-300 rounded-xl shadow-sm px-3 py-2 bg-white" 
                                value="{{ $this->user->last_name ?? ' ' }}" disabled>
                        </div>
                        <div class="w-2/3">
                            <label class="block font-bold text-zinc-700">Suffix</label>
                            <input type="text" class="mt-1 text-sm w-full border border-gray-300 rounded-xl shadow-sm px-3 py-2 bg-white" 
                                value="{{ $this->user->suffix ?? ' ' }}" disabled>
                        </div>
                    </div>

                    <!-- Email Address Field -->
                    <div class="mb-8">
                        <label class="block font-bold text-zinc-700">Email</label>
                        <input type="text" class="mt-1 text-sm w-full border border-gray-300 rounded-xl shadow-sm px-3 py-2 bg-white" 
                            value="{{ $this->user->email ?? ' ' }}" disabled>
                    </div>

                    <div>
                        <button class="w-full px-3 py-2 bg-blue-900 text-white hover:bg-blue-950 font-semibold rounded-xl">Edit Information</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</div>
