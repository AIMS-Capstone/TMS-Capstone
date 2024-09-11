
    <tr>
        <!-- Description -->
        <td class="border">
            <input id="description" type="text" class="block w-full border-none" wire:model="description" placeholder="Enter Description" />
        </td>
    
        <!-- COA (Chart of Accounts) -->
        <td class="border px-4 py-2">
            <select class="block w-full h-full border-none" id="coa" wire:model="coa">
                <option value="" disabled selected>Select Account</option>
                @foreach($coas as $coa)
                    <option value="{{ $coa->id }}">{{ $coa->name }}</option>
                @endforeach
            </select>
        </td>
    
        <!-- Debit -->
        <td class="border px-4 py-2">
            <input id="debit" type="number" class="block w-full border-none" wire:model.blur="debit" placeholder="0.00" min="0" step="0.01" />
        </td>
    
        <!-- Credit -->
        <td class="border px-4 py-2">
            <input id="credit" type="number" class="block w-full border-none" wire:model.blur="credit" placeholder="0.00" min="0" step="0.01" />
        </td>
    
        <!-- Actions (Optional, like Remove row) -->
      
        <td class="border px-4 py-2">
            <button type="button" wire:click.prevent="removeRow" class="text-red-500">Remove</button>
        </td>
    </tr>
    

