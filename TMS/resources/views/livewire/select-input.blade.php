<div class="form-group text-xs">
    <select 
        name="{{ $name }}" 
        class="text-xs select2 {{ $class }} mr-2 @error('selectedValue') border-red-500 @enderror" 
        id="{{ $id }}" 
        @if($isGrouped) data-grouped="true" @endif
        wire:model="selectedValue"
        >
        <!-- Default empty option -->
       <option 
            class="text-sm" value="default" 
            data-tax-id="000-000-000-000"
            {{ empty($selectedValue) || $selectedValue == 'default' ? 'selected' : '' }}
            >
            Default TIN
        </option>

        @foreach($options as $option)
            @if($isGrouped && isset($option['label']))
                <!-- Grouped option -->
                <optgroup label="{{ $option['label'] }}">
                    @foreach($option['options'] as $item)
                        <option 
                            value="{{ $item[$valueKey] ?? '' }}" 
                            {{ $item[$valueKey] == $selectedValue ? 'selected' : '' }} 
                            @if(isset($item['tax_identification_number']) && $item['tax_identification_number']) 
                                data-tax-id="{{ $item['tax_identification_number'] }}"
                            @endif
                        >
                            {{ $item[$labelKey] }}
                        </option>
                    @endforeach
                </optgroup>
            @else
                <!-- Non-grouped option -->
                <option 
                    value="{{ $option[$valueKey] ?? '' }}" 
                    {{ $option[$valueKey] == $selectedValue ? 'selected' : '' }} 
                    @if(isset($option['tax_identification_number']) && $option['tax_identification_number']) 
                        data-tax-id="{{ $option['tax_identification_number'] }}"
                    @endif
                    >
                    {{ $option[$labelKey] }}
                </option>
            @endif
        @endforeach
    </select>
    @error('selectedValue')
    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
@enderror
</div>