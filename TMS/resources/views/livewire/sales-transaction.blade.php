<div>
    <x-transaction-form-section>
        <x-slot:title>
            Sales
        </x-slot:title>

        <x-slot:description>
            Add a New Sales Transaction after filling up the form!
        </x-slot:description>

        <x-slot:form>
            <div class="row flex">
                <div>
                    <label for="select-contact" class="block font-medium text-sm auth-color mb-4"> Vendor Name </label>
                <div class="col-4 mr-2 border-none 
                    placeholder:text-gray-400 
                    placeholder:font-light 
                    placeholder:text-sm 
                    focus:border-slate-500 
                    focus:ring-slate-500 
                    rounded-xl 
                    shadow-sm">
                    <livewire:select-input
                        name="select_contact" 
                        labelKey="name" 
                        valueKey="value" 
                        class="form-control  w-40 select2-input  " 
                        id="select_contact" 
                       
                      
                       
                    />
                </div>
            </div>

                <div class="col-2 mr-2">
                    <x-transaction-label for="date" :value="__('Date')" />
                    <x-transaction-input id="date" type="date" class="mt-1 block w-full" wire:model.defer="date" />
                </div>

                <div class="col-2">
                    <x-transaction-label for="reference" :value="__('Reference')" />
                    <x-transaction-input id="reference" type="text" class="mt-1 block w-full" wire:model.defer="reference" />
                </div>
            </div>

            <div class="row mt-4">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Description</th>
                            <th class="px-12 mx-4 py-2" colspan="3">Tax Type</th>
                            <th class="px-4 py-2">ATC</th>
                            <th class="px-4 py-2">COA</th>
                            <th class="px-4 py-2">Amount (VAT Inclusive)</th>
                            <th class="px-4 py-2">Tax Amount</th>
                            <th class="px-4 py-2">Net Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <!-- Description -->
                            <td class="border">
                                <input id="description" type="text" class="block w-full border-none" wire:model="description" />
                            </td>
                            <!-- Tax Type -->
                            <td class="border px-4 py-2" colspan="3" wire:ignore>
                                <select name="tax_type" class="form-control mt-2 w-20 select2" id="tax_type" wire:model.live="tax_code">
                                    <option value="" disabled selected>Select Tax Type</option>
                                    @foreach($taxTypes as $tax)
                                        <option value="{{ $tax->id }}">{{ $tax->tax_type }} ({{ $tax->VAT }}%)</option>
                                    @endforeach
                                </select>
                            </td>
                            <!-- ATC -->
                            <td class="border px-4 py-2" >
                                <select class="block w-full h-full border-none select2" wire:model.live="tax_code">
                                    <option value="" disabled selected>Select Tax Code</option>
                                    @foreach($atcs as $atc)
                                    <option value="{{ $atc->id }}">{{ $atc->tax_code }} ({{ $atc->tax_rate }}%)</option>
                                    @endforeach
                                </select>
                            </td>
                            <!-- COA -->
                            <td class="border px-4 py-2">
                                <input id="coa" type="text" class="block w-full border-none" wire:model="coa" />
                            </td>
                            <!-- Amount -->
                            <td class="border px-4 py-2">
                                <input id="amount" type="number" class="block w-full border-none" wire:model.live="amount" />
                            </td>
                            <!-- Tax Amount -->
                            <td class="border px-4 py-2">
                                <input id="tax_amount" type="number" class="block w-full border-none" wire:model.live="tax_amount" readonly />
                            </td>
                            <!-- Net Amount -->
                            <td class="border px-4 py-2">
                                <input id="net_amount" type="number" class="block w-full border-none" wire:model="net_amount" readonly />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-slot:form>
        
        <x-slot:actions>
            <x-button type="submit" class="ml-4" wire:click="saveTransaction">
                {{ __('Save') }}
            </x-button>
        </x-slot:actions>
    </x-transaction-form-section>
</div>
