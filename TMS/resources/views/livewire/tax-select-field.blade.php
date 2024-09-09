<td class="border px-4 py-2">
    <select 
        id="{{ $id }}" 
        name="{{ $name }}" 
        class="form-control w-full border-none p-2 select2" 
        wire:model.live="selected"
    >
        <option value="" disabled>Select an option</option>
        @foreach($options as $option)
            <option value="{{ $option->id }}">
                {{ $option->{$labelKey} }}
            </option>
        @endforeach
    </select>
</td>
