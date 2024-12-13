<div wire:ignore>
    <select 
        wire:model.live="selectedATCCode"
        name="{{ $name }}"
        id="{{ $selectId }}"
        class="form-control w-full select2i"
    >*
        <option value="" disabled {{ is_null($selectedATCCode) ? 'selected' : '' }}>Select ATC</option>
        @foreach($atcs as $atc)
            <option value="{{ $atc['id'] }}" {{ $atc['id'] == $selectedATCCode ? 'selected' : '' }}>
                {{ $atc['tax_code'] }} - {{ $atc['description'] }}
            </option>
        @endforeach
    </select>
</div>
@script
<script>
 $('#{{ $selectId }}').off('change').on('change', function () {
    $wire.set('selectedATCCode', $(this).val());
});
</script>
@endscript
