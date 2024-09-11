<tr>
    <!-- Description -->
    <td class="border">
        <input id="description" type="text" class="block w-full border-none" wire:model="description" />
    </td>

    <!-- Tax Type -->
    <td class="border px-4 py-2">
        <select name="tax_type" class="form-control mt-2 w-20 select2" id="tax_type" wire:model.live="tax_type">
            <option value="" disabled selected>Select Tax Type</option>
            @foreach($taxTypes as $tax)
                <option value="{{ $tax->id }}">{{ $tax->tax_type }} ({{ $tax->VAT }}%)</option>
            @endforeach
        </select>
    </td>

    <!-- ATC -->
    <td class="border px-4 py-2">
        <select class="block w-full h-full border-none select2" id="tax_code" wire:model.live="tax_code">
            <option value="" disabled selected>Select Tax Code</option>
            @foreach($atcs as $atc)
                <option value="{{ $atc->id }}">{{ $atc->tax_code }} ({{ $atc->tax_rate }}%)</option>
            @endforeach
        </select>
    </td>

    <!-- COA -->
    <td class="border px-4 py-2">
        <select class="block w-full h-full border-none select2" id="coa" wire:model="coa">
            <option value="" disabled selected>Select COA</option>
            @foreach($coas as $coa)
                <option value="{{ $coa->id }}">{{ $coa->code }} {{ $coa->name }}</option>
            @endforeach
        </select>
    </td>

    <!-- Amount -->
    <td class="border px-4 py-2">
        <input id="amount" type="number" class="block w-full border-none" wire:model.blur="amount" />
    </td>

    <!-- Tax Amount -->
    <td class="border px-4 py-2">
        <input id="tax_amount" type="number" class="block w-full border-none" wire:model.live="tax_amount" readonly />
    </td>

    <!-- Net Amount -->
    <td class="border px-4 py-2">
        <input id="net_amount" type="number" class="block w-full border-none" wire:model="net_amount" readonly />
    </td>

    <td class="border px-4 py-2">
        <button type="button" wire:click.prevent="removeRow" class="text-red-500">Remove</button>
    </td>
</tr>