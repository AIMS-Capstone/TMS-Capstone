<div wire:ignore>
    <select 
        wire:model.live="selectedCoa"
        name="{{ $name }}"
        id="{{ $selectId }}"
        class="{{ $class }}"
    >
        <option value="" disabled {{ is_null($selectedCoa) ? 'selected' : '' }}> Select COA</option>
        @foreach($coas as $coaItem)
            <option value="{{ $coaItem['id'] }}" {{ $coaItem['id'] == $selectedCoa ? 'selected' : '' }}>
                {{ $coaItem['display'] }}
            </option>
        @endforeach
    </select>
</div>
@script
<script>

$('#{{ $selectId }}').off('change').on('change', function () {
    $wire.set('selectedCoa', $(this).val());
});
</script>
@endscript