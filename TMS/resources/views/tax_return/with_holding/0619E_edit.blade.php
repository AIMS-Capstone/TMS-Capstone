<x-app-layout>
    <div class="max-w-6xl mx-auto py-12 px-6">
        <div class="relative flex items-center mb-6">
            <button onclick="window.location.href='{{ route('form0619E.preview', ['id' => $form->id]) }}'" class="text-zinc-600 hover:text-zinc-700">
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
                        <p class="text-sm taxuri-color">BIR Form No. 0619-E Edit</p>
                        <p class="font-bold text-xl taxuri-color">Monthly Remittance Return <span class="text-lg">(of Creditable Income Taxes Withheld (Expanded))</span></p>
                    </div>
                </div>
                <div class="flex justify-between items-center mt-2 mb-4">
                    <div class="flex items-center">
                        <p class="taxuri-text font-normal text-sm">
                            Verify the tax information below, with some fields pre-filled from your organization's setup. Select options as needed, then click 'Proceed to Report' to generate the BIR form. Hover over
                            icons for additional guidance on specific fields.
                        </p>
                    </div>
                </div>  
            </div>
        </div>

        <div class="bg-white shadow-sm mt-6 rounded-lg overflow-hidden">
            <form method="POST" action="{{ route('form0619E.update', ['id' => $form->id]) }}">
                @csrf
                @method('PUT')
                <div class="px-8 py-10">
                    <!-- Filing Period -->
                    <h3 class="font-bold text-zinc-700 text-lg mb-4">Filing Period</h3>
                    <div class="mb-2 flex flex-row justify-between gap-96">
                        <label for="for_month" class="indent-4 block text-zinc-700 text-sm w-1/3">For the Month</label>
                        <input type="month" name="for_month" id="for_month" value="{{ old('for_month', $form->for_month) }}"
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                    </div>

                    <div class="mb-2 flex flex-row justify-between gap-96">
                        <label for="due_date" class="indent-4 block text-zinc-700 text-sm w-1/3">Due Date</label>
                        <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $form->due_date) }}"
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                    </div>

                    <div class="mb-2 flex flex-row justify-between gap-96">
                        <label for="amended_return" class="indent-4 block text-zinc-700 text-sm w-1/3">Amended Return</label>
                        <select name="amended_return" id="amended_return" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                            <option value="1" {{ $form->amended_return == 1 ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ $form->amended_return == 0 ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="mb-2 flex flex-row justify-between gap-16">
                        <label for="any_taxes_withheld" class="indent-4 block text-zinc-700 text-sm w-full">Any Taxes Withheld?</label>
                        <select name="any_taxes_withheld" id="any_taxes_withheld" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                            <option value="1" {{ $form->any_taxes_withheld == 1 ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ $form->any_taxes_withheld == 0 ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="mb-2 flex flex-row justify-between gap-16">
                        <label for="atc_id" class="indent-4 block text-zinc-700 text-sm w-full">Alphanumeric Tax Code (ATC)</label>
                        <select name="atc_id" id="atc_id" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                            <option value="" disabled>Select ATC</option>
                            @foreach($atcs as $atc)
                                <option value="{{ $atc->id }}" {{ old('atc_id', $form->atc_id) == $atc->id ? 'selected' : '' }}>
                                    {{ $atc->tax_code }} - {{ $atc->description }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2 flex flex-row justify-between gap-16">
                        <label class="indent-4 block text-zinc-700 text-sm w-full">Category of Withholding Agent</label>
                        <div class="flex items-center space-x-4 w-full py-2">
                            <label class="flex items-center text-zinc-700 text-sm">
                                <input type="radio" name="category" value="1" class="mr-2"
                                    {{ $form->organization->category == 1 ? 'checked' : '' }} required>
                                Private
                            </label>
                            <label class="flex items-center text-zinc-700 text-sm">
                                <input type="radio" name="category" value="0" class="mr-2"
                                    {{ $form->organization->category == 0 ? 'checked' : '' }} required>
                                Government
                            </label>
                        </div>
                    </div>

                    <div class="mb-2 flex flex-row justify-between gap-16">
                        <label for="tax_code" class="indent-4 block text-zinc-700 text-sm w-full">Tax Code</label>
                        <input type="text" name="tax_code" id="tax_code" value="{{ old('tax_code', $form->tax_code) }}"
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                    </div>

                    <h3 class="font-bold text-zinc-700 text-lg mb-4">Tax Remittance</h3>
                    
                    <div class="mb-2 flex flex-row justify-between gap-16">
                        <label for="amount_of_remittance" class="indent-4 block text-zinc-700 text-sm w-full">Amount of Remittance</label>
                        <input type="number" step="0.01" name="amount_of_remittance" value="{{ old('amount_of_remittance', $form->amount_of_remittance) }}"
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                    </div>

                    <div class="mb-2 flex flex-row justify-between gap-16">
                        <label for="remitted_previous" class="indent-4 block text-zinc-700 text-sm w-full">Less: Amount Remitted Previously</label>
                        <input type="number" step="0.01" name="remitted_previous" value="{{ old('remitted_previous', $form->remitted_previous) }}"
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>

                    <div class="mb-2 flex flex-row justify-between gap-16">
                        <label for="surcharge" class="indent-4 block text-zinc-700 text-sm w-full">Surcharge</label>
                        <input type="number" step="0.01" name="surcharge" value="{{ old('surcharge', $form->surcharge) }}"
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>

                    <div class="mb-2 flex flex-row justify-between gap-16">
                        <label for="interest" class="indent-4 block text-zinc-700 text-sm w-full">Interest</label>
                        <input type="number" step="0.01" name="interest" value="{{ old('interest', $form->interest) }}"
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>

                    <div class="mb-2 flex flex-row justify-between gap-16">
                        <label for="compromise" class="indent-4 block text-zinc-700 text-sm w-full">Compromise</label>
                        <input type="number" step="0.01" name="compromise" value="{{ old('compromise', $form->compromise) }}"
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>
                
                    <div class="mt-8 flex justify-center items-center">
                        <button type="submit" class="w-56 bg-blue-900 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-950">
                            Update Report
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>