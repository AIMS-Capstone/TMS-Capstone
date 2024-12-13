<div wire:ignore>
    <select 
        wire:model.live="selectedTaxType"
        wire:ignore
        name="{{ $name }}"
        id="{{ $selectId }}" 
        class="form-control w-full select2i"
    >
        <option value="" disabled>Select Tax Type</option>
        @foreach($options as $option)
            <option 
                value="{{ $option['value'] }}" 
                data-vat="{{ $option['vat'] }}"
                {{ $selectedTaxType == $option['value'] ? 'selected' : '' }}
            >
                {{ $option['name'] }}
            </option>
        @endforeach
    </select>
</div>
@script
<script>


$('#tax_type_select_{{ $index }}').on('change', function() {
    $wire.set('selectedTaxType', $(this).val());
});

</script>
@endscript

