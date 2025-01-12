<tr class="{{ 
    $errors->has('description.' . $index) || 
    $errors->has('taxRow.tax_type.' . $index) || 
    $errors->has('taxRow.tax_code.' . $index) || 
    $errors->has('taxRow.coa.' . $index) || 
    $errors->has('taxRow.amount.' . $index) 
    ? ' border-t-4 border-b-4 border-red-500' : '' 
}}">
    <!-- Description -->
    <td class="border {{ 
    $errors->has('description.' . $index) || 
    $errors->has('taxRow.tax_type.' . $index) || 
    $errors->has('taxRow.tax_code.' . $index) || 
    $errors->has('taxRow.coa.' . $index) || 
    $errors->has('taxRow.amount.' . $index) 
    ? ' border-l-4  border-red-500' : '' 
}}">
        <input 
            id="description-{{ $index }}" 
            type="text" 
            class="block w-full border-none" 
            wire:model.live="taxRow.description" 
            placeholder="Enter Description" 
        />
 
    </td>

    <!-- Tax Type -->
    <td wire:ignore class="border px-4 py-2">
        <select 
            x-data="select2()"
            class="form-control select2-enabled"
            id="tax_type-{{ $index }}"
            wire:model="taxRow.tax_type"
        >
            <option value="" disabled>Select Tax Type</option>
            @foreach($taxTypes as $tax)
                <option value="{{ $tax->id }}">{{ $tax->tax_type }} ({{ $tax->VAT }}%)</option>
            @endforeach
        </select>
 
    </td>

    <!-- ATC -->
    <td wire:ignore class="border px-4 py-2">
        <select 
            x-data="select2()"
            class="select2-enabled"
            id="tax_code-{{ $index }}" 
            wire:model="taxRow.tax_code"
        >
            <option value="" disabled>Select ATC</option>
            @foreach($atcs as $atc)
                <option value="{{ $atc->id }}">{{ $atc->tax_code }} - {{ $atc->description }} ({{ $atc->tax_rate }}%)</option>
            @endforeach
        </select>
     
    </td>

    <!-- COA -->
    <td wire:ignore class="border px-4 py-2 ">
        <select 
            x-data="select2()"
            class="select2-enabled"
            id="coa-{{ $index }}" 
            wire:model="taxRow.coa"
        >
            <option value="" disabled>Select COA</option>
            @foreach($coas as $coaItem)
                <option value="{{ $coaItem->id }}">{{ $coaItem->code }} - {{ $coaItem->name }}</option>
            @endforeach
        </select>
     
    </td>

    <!-- Amount -->
    <td class="border ">
        <input 
            id="amount-{{ $index }}" 
            type="number" 
            class="block w-full border-none" 
            wire:model.live="taxRow.amount"
            wire:change="calculateTax"
        />
       
    </td>

    <!-- Tax Amount -->
    <td class="border px-4 py-2">
        <input 
            id="tax_amount-{{ $index }}" 
            type="number" 
            class="block w-full border-none" 
            wire:model="taxRow.tax_amount" 
            readonly 
        />
    </td>

    <!-- Net Amount -->
    <td class="border px-4 py-2">
        <input 
            id="net_amount-{{ $index }}" 
            type="number" 
            class="block w-full border-none" 
            wire:model="taxRow.net_amount" 
            readonly 
        />
    </td>

    <!-- Remove Row Button -->
    <td class="border {{ 
        $errors->has('description.' . $index) || 
        $errors->has('taxRow.tax_type.' . $index) || 
        $errors->has('taxRow.tax_code.' . $index) || 
        $errors->has('taxRow.coa.' . $index) || 
        $errors->has('taxRow.amount.' . $index) 
        ? ' border-r-4  border-red-500' : '' 
    }}">
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
document.addEventListener('alpine:init', () => {
    Alpine.data('select2', () => ({
        init() {
            console.log("Alpine select2 init called");
            this.initializeSelect2();
        },
    
        initializeSelect2() {
            console.log("initializeSelect2 method called");
            this.$nextTick(() => {
                const $select = $(this.$el);
                
                console.log("Select element:", $select);
                console.log("Is Select2 initialized:", $select.data('select2') ? 'Yes' : 'No');

                // Destroy existing Select2 instance if present
                if ($select.data('select2')) {
                    $select.select2('destroy');
                }
    
                // Reinitialize Select2 with custom options
                $select.select2({
                    placeholder: "Select",
                    allowClear: true,
                    width: '100%'
                });
    
                $select.on('change', (e) => {
                    console.log("Select2 change event triggered");
                    console.log("Element:", this.$el);
                    console.log("Wire model:", this.$el.getAttribute('wire:model'));
                    console.log("Selected value:", $select.val());
                    
                    // Trigger Livewire's native model update
                    this.$wire.set(this.$el.getAttribute('wire:model'), $select.val());
                });
            });
        }
    }));
});
    
    // Global event listener for Select2 reinitialization
    document.addEventListener('livewire:load', () => {
        Livewire.hook('morph.updated', (el) => {
            setTimeout(() => {
                document.querySelectorAll('select.select2-enabled').forEach(el => {
                    const $el = $(el);
                    if ($el.data('select2')) {
                        $el.select2('destroy');
                    }
                    $el.select2({
                        placeholder: "Select",
                        allowClear: true,
                        width: '100%'
                    });
                });
            }, 100);
        });
    });
    
    // Custom event for manual reinitialization
    document.addEventListener('select2:reinitialize', () => {
        setTimeout(() => {
            document.querySelectorAll('select.select2-enabled').forEach(el => {
                const $el = $(el);
                if ($el.data('select2')) {
                    $el.select2('destroy');
                }
                $el.select2({
                    placeholder: "Select",
                    allowClear: true,
                    width: '100%'
                });
            });
        }, 100);
    });
    </script>