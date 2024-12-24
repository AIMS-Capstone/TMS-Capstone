<tr>
    <!-- Description -->
    <td class="border">
        <input 
            id="description" 
            type="text" 
            class="block w-full border-none" 
            wire:model="description" 
            placeholder="Enter Description" 
            value="{{ $description ?? '' }}"  
        />
    </td>

    <!-- Tax Type -->
    <td class="border px-4 py-2">
        <select 
            name="tax_type" 
            class="form-control mt-2 w-full select2 block py-2.5 px-0 text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
            id="tax_type" 
            wire:model.live="tax_type"
        >
            <option value="" disabled {{ is_null($tax_type) ? 'selected' : '' }}></option>
            @foreach($taxTypes as $tax)
                <option value="{{ $tax->id }}" {{ $tax->id == $tax_type ? 'selected' : '' }}>
                    {{ $tax->tax_type }} ({{ $tax->VAT }}%)
                </option>
            @endforeach
        </select>
    </td>

    <!-- ATC -->
    <td class="border px-4 py-2">
        <select 
            class="block w-full h-full border-none select2 py-2.5 px-0 text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
            id="tax_code" 
            wire:model.live="tax_code"
        >
            <option value="" disabled {{ is_null($tax_code) ? 'selected' : '' }}></option>
            @foreach($atcs as $atc)
                <option value="{{ $atc->id }}" {{ $atc->id == $tax_code ? 'selected' : '' }}>
                    {{ $atc->tax_code }} ({{ $atc->tax_rate }}%)
                </option>
            @endforeach
        </select>
    </td>

    <!-- COA -->
    <td class="border px-4 py-2">
        <select 
            class="block w-full h-full border-none select2 py-2.5 px-0 text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
            id="coa" 
            wire:model="coa"
        >
            <option value="" disabled {{ is_null($coa) ? 'selected' : '' }}></option>
            @foreach($coas as $coaItem)
                <option value="{{ $coaItem->id }}" {{ $coaItem->id == $coa ? 'selected' : '' }}>
                    {{ $coaItem->code }} {{ $coaItem->name }}
                </option>
            @endforeach
        </select>
    </td>

    <!-- Amount -->
    <td class="border px-4 py-2">
        <input 
            id="amount" 
            type="number" 
            class="block w-full border-none" 
            wire:model.blur="amount" 
            value="{{ $amount ?? '' }}"  
        />
    </td>

    <!-- Tax Amount -->
    <td class="border px-4 py-2">
        <input 
            id="tax_amount" 
            type="number" 
            class="block w-full border-none" 
            wire:model.live="tax_amount" 
            readonly 
            value="{{ $tax_amount ?? '' }}" 
        />
    </td>

    <!-- Net Amount -->
    <td class="border px-4 py-2">
        <input 
            id="net_amount" 
            type="number" 
            class="block w-full border-none" 
            wire:model="net_amount" 
            readonly 
            value="{{ $net_amount ?? '' }}"  
        />
    </td>

    <td class="border px-4 py-2">
        <button 
            type="button" 
            wire:click.prevent="removeRow" 
            class="text-red-600 group"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 group-hover:fill-red-600 transition-colors" viewBox="0 0 24 24">
                <g fill="#9ca3af" class="group-hover:fill-red-600">
                    <path d="M16.34 9.322a1 1 0 1 0-1.364-1.463l-2.926 2.728L9.322 7.66A1 1 0 0 0 7.86 9.024l2.728 2.926l-2.927 2.728a1 1 0 1 0 1.364 1.462l2.926-2.727l2.728 2.926a1 1 0 1 0 1.462-1.363l-2.727-2.926z"/>
                    <path fill-rule="evenodd" d="M1 12C1 5.925 5.925 1 12 1s11 4.925 11 11s-4.925 11-11 11S1 18.075 1 12m11 9a9 9 0 1 1 0-18a9 9 0 0 1 0 18" clip-rule="evenodd"/>
                </g>
            </svg>
        </button>
    </td>
</tr>
