<x-app-layout>
    <div class="max-w-6xl mx-auto py-12 px-6">
        <div class="relative flex items-center mb-6">
            <button onclick="window.location.href='{{ route('with_holding.0619E') }}'" class="flex items-center text-zinc-600 hover:text-zinc-800 transition duration-150">
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
                        <p class="text-sm taxuri-color">BIR Form No. 0619-E Preview</p>
                        <p class="font-bold text-xl taxuri-color">Monthly Remittance Return <span class="text-lg">(of Creditable Income Taxes Withheld (Expanded))</span></p>
                    </div>
                    <div class="space-x-2 flex items-center">
                        <a href="{{ route('form0619E.edit', ['id' => $form->id]) }}"
                            class="border px-6 py-2 text-sm text-zinc-600 rounded-lg hover:border-blue-500 hover:text-blue-500 hover:bg-blue-100 transition flex items-center group">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5 transition group-hover:text-blue-500" viewBox="0 0 24 24"><path fill="currentColor" d="M21 12a1 1 0 0 0-1 1v6a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h6a1 1 0 0 0 0-2H5a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3v-6a1 1 0 0 0-1-1m-15 .76V17a1 1 0 0 0 1 1h4.24a1 1 0 0 0 .71-.29l6.92-6.93L21.71 8a1 1 0 0 0 0-1.42l-4.24-4.29a1 1 0 0 0-1.42 0l-2.82 2.83l-6.94 6.93a1 1 0 0 0-.29.71m10.76-8.35l2.83 2.83l-1.42 1.42l-2.83-2.83ZM8 13.17l5.93-5.93l2.83 2.83L10.83 16H8Z"/></svg>
                            Edit
                        </a>
                        <a href="{{ route('form0619E.download', ['id' => $form->id]) }}"
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
                            Verify the tax information below, with some fields pre-filled from your organization's setup. Select options as needed, then click 'Proceed to Report' to generate the BIR form. Hover over
                            icons for additional guidance on specific fields.
                        </p>
                    </div>
                </div>  
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg mt-6 overflow-hidden">
            <div class="px-8 py-10">
                <div class="border-b pb-8 mb-8">
                    <h3 class="font-bold text-zinc-700 text-lg mb-4">Background Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <dt class="text-sm font-bold text-zinc-600">Taxpayer Identification Number (TIN)</dt>
                            <dd class="text-lg text-zinc-800">{{ $form->organization->tin ?? 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-bold text-zinc-600">RDO Code</dt>
                            <dd class="text-md text-zinc-800">{{ $form->organization->rdo ?? 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-bold text-zinc-600">Withholding Agent's Name</dt>
                            <dd class="text-md text-zinc-800">{{ $form->organization->registration_name ?? 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-bold text-zinc-600">Registered Address</dt>
                            <dd class="text-md text-zinc-800">{{ $form->organization->address_line ?? 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-bold text-zinc-600">Zip Code</dt>
                            <dd class="text-md text-zinc-800">{{ $form->organization->zip_code ?? 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-bold text-zinc-600">Contact Number</dt>
                            <dd class="text-md text-zinc-800">{{ $form->organization->contact_number ?? 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-bold text-zinc-600">Email</dt>
                            <dd class="text-md text-zinc-800">{{ $form->organization->email ?? 'N/A' }}</dd>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <dt class="text-sm font-bold text-zinc-600">For the Month</dt>
                        <dd class="text-md text-zinc-800">{{ $form->for_month }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-bold text-zinc-600">Due Date</dt>
                        <dd class="text-md text-zinc-800">{{ $form->due_date }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-bold text-zinc-600">Alphanumeric Tax Code (ATC)</dt>
                        <dd class="text-md text-zinc-800">{{ $form->atc->tax_code ?? 'N/A' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-bold text-zinc-600">Tax Code</dt>
                        <dd class="text-md text-zinc-800">{{ $form->tax_code }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-bold text-zinc-600">Amount of Remittance</dt>
                        <dd class="text-md text-zinc-800">
                            ₱{{ number_format($form->amount_of_remittance, 2) }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-bold text-zinc-600">Remitted Previously</dt>
                        <dd class="text-md text-zinc-800">
                            ₱{{ number_format($form->remitted_previous, 2) }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-bold text-zinc-600">Net Amount of Remittance</dt>
                        <dd class="text-md text-zinc-800">
                            ₱{{ number_format($form->net_amount_of_remittance, 2) }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-bold text-zinc-600">Surcharge</dt>
                        <dd class="text-md text-zinc-800">
                            ₱{{ number_format($form->surcharge, 2) }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-bold text-zinc-600">Interest</dt>
                        <dd class="text-md text-zinc-800">
                            ₱{{ number_format($form->interest, 2) }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-bold text-zinc-600">Compromise</dt>
                        <dd class="text-md text-zinc-800">
                            ₱{{ number_format($form->compromise, 2) }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-bold text-zinc-600">Total Penalties</dt>
                        <dd class="text-md text-zinc-800">
                            ₱{{ number_format($form->total_penalties, 2) }}
                        </dd>
                    </div>

                    <div class="col-span-1 md:col-span-2 p-4 mt-8">
                        <dt class="text-lg font-bold text-zinc-700">Total Amount Due</dt>
                        <dd class="text-3xl font-extrabold text-blue-900">
                            ₱{{ number_format($form->total_amount_due, 2) }}
                        </dd>
                    </div>
                </div>
            </div>
        </div>

            </div>
        </div>
    </div>
</x-app-layout>
