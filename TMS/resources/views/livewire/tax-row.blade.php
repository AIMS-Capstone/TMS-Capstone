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

    <td class="border px-4 py-2">
        <livewire:tax-type-select
        name="tax_type"
        labelKey="name"
        valueKey="value"
        class="form-control w-full select2i"
        :index="$index"
        :selectedTaxType="$tax_type"
    />
    </td>

    <!-- ATC -->
    <td class="border px-4 py-2">
        <livewire:atc-select 
            name="tax_code"
            :index="$index"
            :transaction-type="$type"  
            :selected-a-t-c-code="$tax_code" 
        />
    </td>
    
    </td>

    <!-- COA -->
    <td class="border px-4 py-2">
<livewire:coa-select 
    name="coa"
    :index="$index"
         class="form-control w-full select2i"
    :status="'Active'"  
    :selected-coa="$coa"  
/>
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

<script>
        $(document).ready(function() {
    $('.select2i').select2();
});

document.addEventListener('initialize-select2', (event) => {
    console.log('Re-initializing Select2');
    
    // Initialize Select2 for all the tax rows
    $(document).ready(function() {
        $('.select2i').each(function() {
            $(this).select2({
                dropdownParent: $(this).closest('tr')
            });
        });
    });
});

    
</script>