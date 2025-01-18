<tr>
    <!-- Description -->
    <td class="border">
        <input id="description" type="text" class="block w-full border-none" wire:model.live="description" placeholder="Enter Description" />
    </td>

    <!-- COA (Chart of Accounts) -->
    <td class="border px-4 py-2">
        <select class="block w-full h-full border-none py-2.5 px-0 text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-gray-200 peer" id="coa" wire:model.live="coa">
            <option value="" disabled selected></option>
            @foreach($coas as $coa)
                <option value="{{ $coa->id }}">{{ $coa->name }}</option>
            @endforeach
        </select>
    </td>

    <!-- Debit -->
    <td class="border px-4 py-2">
        <input id="debit" type="number" class="block w-full border-none" wire:model.live.blur="debit" placeholder="0.00" min="0" step="0.01" />
    </td>

    <!-- Credit -->
    <td class="border px-4 py-2">
        <input id="credit" type="number" class="block w-full border-none" wire:model.live.blur="credit" placeholder="0.00" min="0" step="0.01" />
    </td>

    <!-- Actions (Optional, like Remove row) -->
    
    <td class="border px-4 py-2">
        <button type="button" wire:click.prevent="removeRow" class="text-red-600 group ">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 group-hover:fill-red-600 transition-colors" viewBox="0 0 24 24">
                <g fill="#9ca3af" class="group-hover:fill-red-600">
                    <path d="M16.34 9.322a1 1 0 1 0-1.364-1.463l-2.926 2.728L9.322 7.66A1 1 0 0 0 7.86 9.024l2.728 2.926l-2.927 2.728a1 1 0 1 0 1.364 1.462l2.926-2.727l2.728 2.926a1 1 0 1 0 1.462-1.363l-2.727-2.926z"/>
                    <path fill-rule="evenodd" d="M1 12C1 5.925 5.925 1 12 1s11 4.925 11 11s-4.925 11-11 11S1 18.075 1 12m11 9a9 9 0 1 1 0-18a9 9 0 0 1 0 18" clip-rule="evenodd"/>
                </g>
            </svg>
        </button>
    </td>
</tr>
    

