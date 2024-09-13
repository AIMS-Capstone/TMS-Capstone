<tr>
    <!-- Description -->
    <td class="border">
        <input id="description" type="text" class="block w-full border-none" wire:model="description" />
    </td>

    <!-- Tax Type -->
    <td class="border px-4 py-2">
        <select name="tax_type" class="form-control mt-2 w-full select2 block py-2.5 px-0 text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-gray-200 peer" id="tax_type" wire:model.live="tax_type">
            <option value="" disabled selected></option>
            @foreach($taxTypes as $tax)
                <option value="{{ $tax->id }}">{{ $tax->tax_type }} ({{ $tax->VAT }}%)</option>
            @endforeach
        </select>
    </td>

    <!-- ATC -->
    <td class="border px-4 py-2">
        <select class="block w-full h-full border-none select2 py-2.5 px-0 text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-gray-200 peer" id="tax_code" wire:model.live="tax_code">
            <option value="" disabled selected></option>
            @foreach($atcs as $atc)
                <option value="{{ $atc->id }}">{{ $atc->tax_code }} ({{ $atc->tax_rate }}%)</option>
            @endforeach
        </select>
    </td>

    <!-- COA -->
    <td class="border px-4 py-2">
        <select class="block w-full h-full border-none select2 py-2.5 px-0 text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-gray-200 peer" id="coa" wire:model="coa">
            <option value="" disabled selected></option>
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
