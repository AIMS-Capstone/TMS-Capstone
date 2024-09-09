<td class="border px-4 py-2">
    <input 
        id="{{ $id }}" 
        type="{{ $type }}" 
        class="block w-full border-none p-2" 
        wire:model.live="value" 
        value="{{ $value }}" 
        {{ $readonly ? 'readonly' : '' }}
    />
</td>
