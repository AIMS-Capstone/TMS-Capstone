<div 
    x-data
    x-init="$nextTick(() => initSelect2())">
    {{-- Region Dropdown --}}
    <div class="flex flex-col mb-4 w-full max-w-md">
        <div class="flex flex-col">
            <x-field-label for="region" value="{{ __('Region') }}" class="mb-2 text-left" />
            <select
                name="region"
                id="region"
                x-model="formData.region"
                class="select2 cursor-pointer border rounded-xl px-4 py-2 w-full text-sm border-gray-300 placeholder:text-gray-400 placeholder:font-light placeholder:text-sm focus:border-slate-500 focus:ring-slate-500 shadow-sm"
            >
                <option value="" disabled selected>Select Region</option>
                @foreach($regions as $region)
                    <option value="{{ $region['designation'] }}">{{ $region['name'] }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Province Dropdown --}}
    <div class="flex flex-col mb-4 w-full max-w-md">
        <div class="flex flex-col">
            <x-field-label for="province" value="{{ __('Province') }}" class="mb-2 text-left" />
            <select
                name="province"
                id="province"
                x-model="formData.province"
                class="select2 cursor-pointer border rounded-xl px-4 py-2 w-full text-sm border-gray-300 placeholder:text-gray-400 placeholder:font-light placeholder:text-sm focus:border-slate-500 focus:ring-slate-500 shadow-sm"
                @if(!$selectedRegion) disabled @endif
            >
                <option value="" disabled selected>Select Province</option>
                @foreach($provinces as $province)
                    <option value="{{ $province['name'] }}">{{ $province['name'] }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- City Dropdown and Zip Code --}}
    <div class="flex flex-row space-x-4 w-full max-w-md">
        <div class="flex flex-col w-full">
            <x-field-label for="city" value="{{ __('City') }}" class="mb-2 text-left" />
            <select
                name="city"
                id="city"
                x-model="formData.city"
                class="select2 cursor-pointer border rounded-xl px-4 py-2 w-full text-sm border-gray-300 placeholder:text-gray-400 placeholder:font-light placeholder:text-sm focus:border-slate-500 focus:ring-slate-500 shadow-sm"
                @if(!$selectedProvince) disabled @endif
            >
                <option value="" disabled selected>Select City</option>
                @foreach($cities as $city)
                    <option value="{{ $city['name'] }}">{{ $city['name'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col w-32">
            <x-field-label for="zip_code" value="{{ __('Zip Code') }}" class="mb-2 text-left" />
            <x-input
                type="text"
                name="zip_code"
                id="zip_code"
                x-model="formData.zip_code"
                wire:model="zipCode"
                placeholder="1203"
                class="border rounded-xl px-4 py-2 w-full"
                readonly
                maxlength="4"
            />
        </div>
    </div>
</div>

<script>
let initializingSelect2 = false;
function initSelect2() {
    if (initializingSelect2) return;
    initializingSelect2 = true;

    try {
        // Region Select2
        if (!$('#region.select2').data('select2')) {
            $('#region.select2').select2({
                dropdownAutoWidth: true,
                dropdownParent: $(document.body)
            }).on('change', function (e) {
                const selectedValue = $(this).val();

                // Clear dependent fields and reset placeholders
                resetDropdown('#province', "Select Province");
                resetDropdown('#city', "Select City");

                // Dispatch Livewire event
                Livewire.dispatch('selectedRegion', { value: selectedValue });
            });
        }

        // Province Select2
        if (!$('#province.select2').data('select2')) {
            $('#province.select2').select2({
                dropdownAutoWidth: true,
                dropdownParent: $(document.body)
            }).on('change', function (e) {
                const selectedValue = $(this).val();

                // Clear dependent field and reset placeholder
                resetDropdown('#city', "Select City");

                // Dispatch Livewire event
                Livewire.dispatch('selectedProvince', { value: selectedValue });
            });
        }

        // City Select2
        if (!$('#city.select2').data('select2')) {
            $('#city.select2').select2({
                dropdownAutoWidth: true,
                dropdownParent: $(document.body)
            }).on('change', function (e) {
                const selectedValue = $(this).val();
                Livewire.dispatch('selectedCity', { value: selectedValue });
            });
        }
    } catch (error) {
        console.error("Error initializing Select2:", error);
    } finally {
        initializingSelect2 = false;
    }
}

// Helper function to reset dropdown and ensure placeholder selection
function resetDropdown(selector, placeholderText) {
    const $dropdown = $(selector);
    $dropdown.val(null).trigger('change'); // Clear selection
    $dropdown.html(`<option value="" disabled selected>${placeholderText}</option>`); // Reset placeholder
}

// Initialize when Livewire loads
document.addEventListener('livewire:init', () => {
    initSelect2();
});

// Listen for the custom reinitSelect2 event
document.addEventListener('reinitSelect2', () => {
    try {
        const selects = ['#city', '#region', '#province'];
        selects.forEach(selector => {
            if ($(selector + '.select2').data('select2')) {
                $(selector + '.select2').select2('destroy');
            }
        });
        
        requestAnimationFrame(() => {
            initSelect2();
        });
    } catch (error) {
        console.error("Error during reinit:", error);
    }
});
</script>