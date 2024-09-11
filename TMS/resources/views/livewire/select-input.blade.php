
<div class="form-group">
    <select 
 

        name="{{ $name }}" 
        class="select2 {{ $class }} mr-2" 
        id="{{ $id }}"


        @if($isGrouped) data-grouped="true" @endif
    >
        @foreach($options as $option)
            @if($isGrouped && isset($option['label']))
                <optgroup label="{{ $option['label'] }}">
                    @foreach($option['options'] as $item)
                        <option value="{{ $item[$valueKey] }}" 
                                @if(isset($item['tax_identification_number']) && $item['tax_identification_number']) 
                                    data-tax-id="{{ $item['tax_identification_number'] }}"
                                @endif
                        >
                            {{ $item[$labelKey] }}
                        </option>
                    @endforeach
                </optgroup>
            @else
                <option value="{{ $option[$valueKey] }}" 
                        @if(isset($option['tax_identification_number']) && $option['tax_identification_number']) 
                            data-tax-id="{{ $option['tax_identification_number'] }}"
                        @endif
                >
                    {{ $option[$labelKey] }}
                </option>
            @endif
        @endforeach
    </select>
</div>

