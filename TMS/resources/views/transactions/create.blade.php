<x-app-layout>
    
    <x-transaction-form-section>
        <x-slot:title>
            Sales
        </x-slot:title>

        <x-slot:description>
            Add a New Sales Transaction after filling up the form!
            <livewire:dynamic-modal />
        </x-slot:description>

        <x-slot:form>
            <div class="row flex">
                <div class="col-2 mt-8" >

                    <livewire:select-input 
                        name="select_contact" 
                        labelKey="name" 
                        valueKey="value" 
                        class="form-control mt-2 w-20 select2-input" 
                        id="select_contact" 
                       
                      
                       
                    />
                </div>
                
                <div class="col-2">
                    <x-transaction-label for="date" :value="__('Date')" />
                    <x-transaction-input id="date" type="date" class="mt-1 block w-full" wire:model.defer="date" />
                    <x-transaction-input-error for="date" class="mt-2" />
                </div>

                <div class="col-2">
                    <x-transaction-label for="reference" :value="__('Reference')" />
                    <x-transaction-input id="reference" type="text" class="mt-1 block w-full" wire:model.defer="reference" />
                    <x-transaction-input-error for="reference" class="mt-2" />
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
                            <td class="border">
                                <input id="description" type="text" class="block w-full border-none" wire:model.defer="description" />
                            </td>
                            <td class="border px-4 py-2" colspan="3">

                             
                            </td>
                            <td class="border px-4 py-2">
                                <select class="block w-full h-full border-none" wire:model.defer="tax_code">
                                    <option value="" disabled selected>Select Tax Type</option>
                                    <option value="VQ010">VQ010 - VAT on mining and quarrying</option>
                                </select>
                            </td>
                            <td class="border px-4 py-2">
                                <input id="coa" type="text" class="block w-full border-none" wire:model.defer="coa" />
                            </td>
                            <td class="border px-4 py-2">
                                <input id="amount" type="number" class="block w-full border-none" wire:model.defer="amount" />
                            </td>
                            <td class="border px-4 py-2">
                                <input id="tax_amount" type="number" class="block w-full border-none" wire:model.defer="tax_amount" readonly/>
                            </td>
                            <td class="border px-4 py-2">
                                <input id="net_amount" type="number" class="block w-full border-none" wire:model.defer="net_amount" readonly/>
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

    

</x-app-layout>
