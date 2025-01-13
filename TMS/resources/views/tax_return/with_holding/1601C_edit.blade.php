<x-app-layout>
    <div class="max-w-6xl mx-auto py-12 px-6">
        <div class="relative flex items-center mb-6">
            <button onclick="window.location.href='{{ route('form1601C.preview', ['id' => $form->id]) }}'" class="text-zinc-600 hover:text-zinc-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" viewBox="0 0 24 24">
                    <g fill="none" stroke="#52525b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M16 12H8m4-4l-4 4l4 4"/></g>
                </svg>
                <span class="text-zinc-600 text-sm font-normal hover:text-zinc-700">Go Back</span>
            </button>
        </div>

        <div class="px-6 py-4 bg-white shadow-sm sm:rounded-lg">
            <div class="container px-4">
                <div class="flex justify-between items-center mt-2">
                    <div class="flex flex-col items-start">
                        <!-- BIR Form text on top -->
                        <p class="text-sm taxuri-color">BIR Form No. 1601-C Edit</p>
                        <p class="font-bold text-xl taxuri-color">Monthly Remittance Return <span class="text-lg">(of Income Taxes Withheld on Compensation)</span></p>
                    </div>
                </div>
                <div class="flex justify-between items-center mt-2 mb-4">
                    <div class="flex items-center">
                        <p class="taxuri-text font-normal text-sm">
                            Verify the tax information below, with some fields pre-filled from your organization's setup. Select options as needed to generate the BIR form.
                    </div>
                </div>  
            </div>
        </div>

        <div class="bg-white shadow-sm mt-6 rounded-lg overflow-hidden">
            

            <form action="{{ route('form1601C.update', ['id' => $form->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="px-8 py-10">
                    <!-- Filing Period -->
                    <h3 class="font-bold text-zinc-700 text-lg mb-4">Filing Period</h3>

                    <div class="mb-2 flex flex-row justify-between">
                        <label for="number_of_sheets" class="indent-4 block text-zinc-700 text-sm w-full">Number of Sheets Attached <span class="text-red-500">*</span></label>
                        <input type="number" id="number_of_sheets" name="number_of_sheets"
                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                            value="{{ $form->number_of_sheets }}" min="0" required>
                    </div>
                    <!-- Amended Return -->
                    <div class="mb-2 flex flex-row justify-between">
                        <label class="indent-4 block text-zinc-700 text-sm w-full">Amended Return? <span class="text-red-500">*</span></label>
                        <div class="flex items-center space-x-4 w-full py-2">
                            <label class="flex items-center text-zinc-700 text-sm">
                                <input type="radio" name="amended_return" value="1" 
                                    {{ $form->amended_return == 1 ? 'checked' : '' }} required>
                                <span class="ml-2">Yes</span>
                            </label>
                            <label class="flex items-center text-zinc-700 text-sm">
                                <input type="radio" name="amended_return" value="0" 
                                    {{ $form->amended_return == 0 ? 'checked' : '' }} required>
                                <span class="ml-2">No</span>
                            </label>
                        </div>
                    </div>
                    <!-- Any Taxes Withheld -->
                    <div class="mb-2 flex flex-row justify-between">
                        <label class="indent-4 block text-zinc-700 text-sm w-full">Any Taxes Withheld? <span class="text-red-500">*</span></label>
                        <div class="flex items-center space-x-4 w-full py-2">
                            <label class="flex items-center text-zinc-700 text-sm">
                                <input type="radio" name="any_taxes_withheld" value="1" 
                                    {{ $form->any_taxes_withheld == 1 ? 'checked' : '' }} required>
                                <span class="ml-2">Yes</span>
                            </label>
                            <label class="flex items-center text-zinc-700 text-sm">
                                <input type="radio" name="any_taxes_withheld" value="0" 
                                    {{ $form->any_taxes_withheld == 0 ? 'checked' : '' }} required>
                                <span class="ml-2">No</span>
                            </label>
                        </div>
                    </div>

                    <!-- Agent Category -->
                    <div class="mb-2 flex flex-row justify-between">
                        <label for="agent_category" class="indent-4 block text-zinc-700 text-sm w-full">Category of Withholding Agent <span class="text-red-500">*</span></label>
                        <select id="agent_category" name="agent_category"
                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                            required>
                            <option value="Private" {{ $form->agent_category === 'Private' ? 'selected' : '' }}>Private</option>
                            <option value="Government" {{ $form->agent_category === 'Government' ? 'selected' : '' }}>Government</option>
                        </select>
                    </div>

                    <!-- ATC Details -->
                    <div class="mb-2 flex flex-row justify-between">
                        <label for="atc_id" class="indent-4 block text-zinc-700 text-sm w-full">Alphanumeric Tax Code (ATC) <span class="text-red-500">*</span></label>
                        <select id="atc_id" name="atc_id"
                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                            required>
                            <option value="" disabled>Select Tax Code</option>
                            @foreach ($atcs as $atc)
                                <option value="{{ $atc->id }}" {{ $form->atc_id == $atc->id ? 'selected' : '' }}>
                                    {{ $atc->tax_code }} - {{ $atc->description }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tax Relief -->
                    <div class="mb-2 flex flex-row justify-between">
                        <label class="indent-4 block text-zinc-700 text-sm w-full">Are there payees availing of tax relief under Special Law or International Tax Treaty?<span class="text-red-500">*</span></label>
                        <div class="flex items-center space-x-4 w-full py-2">
                            <label class="flex items-center text-zinc-700 text-sm">
                                <input type="radio" name="tax_relief" value="1" {{ $form->tax_relief ? 'checked' : '' }} required>
                                <span class="ml-2">Yes</span>
                            </label>
                            <label class="flex items-center text-zinc-700 text-sm">
                                <input type="radio" name="tax_relief" value="0" {{ !$form->tax_relief ? 'checked' : '' }} required>
                                <span class="ml-2">No</span>
                            </label>
                        </div>
                    </div>
                    @if ($form->tax_relief)
                        <div class="mt-4">
                            <label for="tax_relief_details" class="block text-sm font-medium text-gray-700">
                                Specify Tax Relief
                            </label>
                            <input type="text" id="tax_relief_details" name="tax_relief_details"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                value="{{ $form->tax_relief_details }}">
                        </div>
                    @endif

                    <!-- Computation -->
                    <h3 class="font-bold text-zinc-700 text-lg mb-4">Computation of Tax</h3>
                    <div class="mb-2 flex flex-row justify-between">
                        <label for="adjustment_taxes_withheld" class="indent-4 block text-zinc-700 text-sm w-full">Adjustment of Taxes Withheld</label>
                        <input type="number" id="adjustment_taxes_withheld" name="adjustment_taxes_withheld"
                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                            value="{{ $form->adjustment_taxes_withheld }}" step="0.01">
                    </div>
                    <div class="mb-2 flex flex-row justify-between">
                        <label for="tax_remitted_return" class="indent-4 block text-zinc-700 text-sm w-full">Tax Remitted in Return Previously Filed</label>
                        <input type="number" id="tax_remitted_return" name="tax_remitted_return"
                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                            value="{{ $form->tax_remitted_return }}" step="0.01">
                    </div>

                    <!-- Penalties -->
                    <h3 class="font-bold text-zinc-700 text-lg mb-4">Computation of Tax</h3>
                    <div class="mb-2 flex flex-row justify-between">
                        <label for="surcharge" class="indent-4 block text-zinc-700 text-sm w-full">Surcharge</label>
                        <input type="number" id="surcharge" name="surcharge"
                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                            value="{{ $form->surcharge }}" step="0.01">
                    </div>
                    <div class="mb-2 flex flex-row justify-between">
                        <label for="interest" class="indent-4 block text-zinc-700 text-sm w-full">Interest</label>
                        <input type="number" id="interest" name="interest"
                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                            value="{{ $form->interest }}" step="0.01">
                    </div>
                    <div class="mb-2 flex flex-row justify-between">
                        <label for="compromise" class="indent-4 block text-zinc-700 text-sm w-full">Compromise</label>
                        <input type="number" id="compromise" name="compromise"
                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                            value="{{ $form->compromise }}" step="0.01">
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-center items-center">
                        <button type="submit" class="w-56 bg-blue-900 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-950">
                            Update Report
                        </button>
                        <a href="{{ route('form1601C.preview', ['id' => $form->id]) }}"
                            class="ml-4 text-zinc-600 hover:text-zinc-900 hover:font-bold font-medium py-2 px-4">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
            
        </div>
    </div>
</x-app-layout>
