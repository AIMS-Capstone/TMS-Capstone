<x-app-layout>
    <div class="max-w-6xl mx-auto py-12 px-6">
        <div class="relative flex items-center mb-6">
            <button onclick="window.location.href='{{ route('with_holding.1601C') }}'" class="flex items-center text-zinc-600 hover:text-zinc-800 transition duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                    <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M16 12H8m4-4l-4 4l4 4"/>
                    </g>
                </svg>
                <span class="text-sm">Go Back</span>
            </button>
        </div>

        <div class="px-6 py-4 bg-white shadow-sm sm:rounded-lg">
            <div class="container px-4">
                <div class="flex justify-between items-center mt-2">
                    <div class="flex flex-col items-start">
                        <!-- BIR Form text on top -->
                        <p class="text-sm taxuri-color">BIR Form No.1601-C Preview</p>
                        <p class="font-bold text-xl taxuri-color">Monthly Remittance Return <span class="text-lg">(of Income Taxes Withheld on Compensation)</span></p>
                    </div>
                    <div class="space-x-2 flex items-center">
                        <a href="{{ route('form1601C.edit', ['id' => $form->id]) }}"
                            class="border px-6 py-2 text-sm text-zinc-600 rounded-lg hover:border-blue-500 hover:text-blue-500 hover:bg-blue-100 transition flex items-center group">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5 transition group-hover:text-blue-500" viewBox="0 0 24 24"><path fill="currentColor" d="M21 12a1 1 0 0 0-1 1v6a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h6a1 1 0 0 0 0-2H5a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3v-6a1 1 0 0 0-1-1m-15 .76V17a1 1 0 0 0 1 1h4.24a1 1 0 0 0 .71-.29l6.92-6.93L21.71 8a1 1 0 0 0 0-1.42l-4.24-4.29a1 1 0 0 0-1.42 0l-2.82 2.83l-6.94 6.93a1 1 0 0 0-.29.71m10.76-8.35l2.83 2.83l-1.42 1.42l-2.83-2.83ZM8 13.17l5.93-5.93l2.83 2.83L10.83 16H8Z"/></svg>
                            Edit
                        </a>
                        <a href="{{ route('form1601C.download', ['id' => $form->id]) }}">
                            <button type="button" class="border px-3 py-2 text-sm text-zinc-600 rounded-lg hover:border-green-500 hover:text-green-500 hover:bg-green-100 transition flex items-center group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5 transition group-hover:text-green-500" viewBox="0 0 24 24">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2M7 11l5 5l5-5m-5-7v12"/>
                                </svg> 
                                <span class="text-zinc-600 transition group-hover:text-green-500">Download PDF</span>
                            </button>
                        </a>
                    </div>
                </div>
                <div class="flex justify-between items-center mt-2 mb-4">
                    <div class="flex items-center">
                        <p class="taxuri-text font-normal text-sm">
                            Verify the tax information below, with some fields pre-filled from your organization's setup. Select options as needed to generate the BIR form.
                        </p>
                    </div>
                </div>  
            </div>
        </div>

        <div class="bg-white shadow-sm mt-6 rounded-lg overflow-hidden">
            <div class="px-8 py-10">
                <h3 class="font-bold text-zinc-700 text-lg mb-4">Filing Period</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Withholding Details -->
                    <div>
                        <label class="text-sm font-bold text-zinc-600">Filing Period</label>
                        <p class="text-sm text-zinc-800">{{ $form->filing_period }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-zinc-600">Amended Return</label>
                        <p class="text-sm text-zinc-800">{{ $form->amended_return ? 'Yes' : 'No' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-zinc-600">Any Taxes Withheld</label>
                        <p class="text-sm text-zinc-800">{{ $form->any_taxes_withheld ? 'Yes' : 'No' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-zinc-600">Number of Sheets Attached</label>
                        <p class="text-sm text-zinc-800">{{ $form->number_of_sheets }}</p>
                    </div>

                    <!-- Organization Information -->
                    <h3 class="font-bold text-zinc-700 text-lg mb-4">Background Information</h3>
                    <div>
                        <label class="text-sm font-bold text-zinc-600">Withholding Agent</label>
                        <p class="text-sm text-zinc-800">{{ $form->organization->registration_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-zinc-600">Taxpayer Identification Number (TIN)</label>
                        <p class="text-sm text-zinc-800">{{ $form->organization->tin ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-zinc-600">Year</label>
                        <p class="text-sm text-zinc-800">{{ $form->year ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-zinc-600">RDO Code</label>
                        <p class="text-sm text-zinc-800">{{ $form->organization->rdo ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-zinc-600">Registered Address</label>
                        <p class="text-sm text-zinc-800">{{ $form->organization->address_line ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-zinc-600">Zip Code</label>
                        <p class="text-sm text-zinc-800">{{ $form->organization->zip_code ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-zinc-600">Contact Number</label>
                        <p class="text-sm text-zinc-800">{{ $form->organization->contact_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-zinc-600">Email</label>
                        <p class="text-sm text-zinc-800">{{ $form->organization->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-zinc-600">Agent Type</label>
                        <p class="text-sm text-zinc-800">{{ $form->organization->type ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- ATC Details -->
                <div class="my-8">
                    <h3 class="font-bold text-zinc-700 text-lg mb-4">ATC Details</h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-bold text-zinc-600">Tax Code</label>
                            <p class="text-sm text-zinc-800">{{ $form->atc->tax_code }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-bold text-zinc-600">Description</label>
                            <p class="text-sm text-zinc-800">{{ $form->atc->description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Computation Summary -->
                <div class="mb-8">
                    <h3 class="font-bold text-zinc-700 text-lg mb-4">Computation Summary</h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-bold text-zinc-600">Total Compensation</label>
                            <p class="text-sm text-zinc-800">₱{{ number_format($form->total_compensation, 2) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-bold text-zinc-600">Taxable Compensation</label>
                            <p class="text-sm text-zinc-800">₱{{ number_format($form->taxable_compensation, 2) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-bold text-zinc-600">Tax Due</label>
                            <p class="text-sm text-zinc-800">₱{{ number_format($form->tax_due, 2) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-bold text-zinc-600">Other Remittances</label>
                            <p class="text-sm text-zinc-800">₱{{ number_format($form->other_remittances, 2) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-bold text-zinc-600">Surcharge</label>
                            <p class="text-sm text-zinc-800">₱{{ number_format($form->surcharge, 2) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-bold text-zinc-600">Interest</label>
                            <p class="text-sm text-zinc-800">₱{{ number_format($form->interest, 2) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-bold text-zinc-600">Compromise</label>
                            <p class="text-sm text-zinc-800">₱{{ number_format($form->compromise, 2) }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="text-sm font-bold text-zinc-600">Total Amount Due</label>
                        <p class="text-lg font-bold text-blue-900">₱{{ number_format($form->total_amount_due, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
