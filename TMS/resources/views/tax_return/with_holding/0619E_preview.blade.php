<x-app-layout>
    <div class="max-w-6xl mx-auto py-12 px-6">
        <div class="relative flex items-center mb-6">
            <button onclick="history.back()" class="flex items-center text-gray-600 hover:text-gray-800 transition duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                    <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M16 12H8m4-4l-4 4l4 4"/>
                    </g>
                </svg>
                <span class="text-sm font-medium">Go Back</span>
            </button>
        </div>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-8 py-10">
                <div class="flex items-center justify-between mb-12">
                    <h1 class="text-4xl font-extrabold text-gray-800 leading-tight">
                        0619E Form Preview
                    </h1>
                    <div class="space-x-4 flex items-center">

                        <a href="{{ route('form0619E.edit', ['id' => $form->id]) }}"
                        class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-5-8l6 6M15 4v3a1 1 0 001 1h3" />
                            </svg>
                            Edit
                        </a>

                        <a href="{{ route('form0619E.download', ['id' => $form->id]) }}"
                            class="inline-flex items-center px-6 py-2 bg-green-600 text-white rounded-lg shadow-md hover:bg-green-700 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v8m4-4H8m8 0l-4 4m4-4l-4-4" />
                                </svg>
                            Download PDF
                        </a>

                    </div>
                </div>

                <div class="border-b pb-8 mb-8">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Background Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <dt class="text-sm font-semibold text-gray-600">Taxpayer Identification Number (TIN)</dt>
                            <dd class="text-lg font-medium text-gray-800">{{ $form->organization->tin ?? 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-semibold text-gray-600">RDO Code</dt>
                            <dd class="text-lg font-medium text-gray-800">{{ $form->organization->rdo ?? 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-semibold text-gray-600">Withholding Agent's Name</dt>
                            <dd class="text-lg font-medium text-gray-800">{{ $form->organization->registration_name ?? 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-semibold text-gray-600">Registered Address</dt>
                            <dd class="text-lg font-medium text-gray-800">{{ $form->organization->address_line ?? 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-semibold text-gray-600">Zip Code</dt>
                            <dd class="text-lg font-medium text-gray-800">{{ $form->organization->zip_code ?? 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-semibold text-gray-600">Contact Number</dt>
                            <dd class="text-lg font-medium text-gray-800">{{ $form->organization->contact_number ?? 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-semibold text-gray-600">Email</dt>
                            <dd class="text-lg font-medium text-gray-800">{{ $form->organization->email ?? 'N/A' }}</dd>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <dt class="text-sm font-semibold text-gray-600">For Month</dt>
                        <dd class="text-lg font-medium text-gray-800">{{ $form->for_month }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-semibold text-gray-600">Due Date</dt>
                        <dd class="text-lg font-medium text-gray-800">{{ $form->due_date }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-semibold text-gray-600">Alphanumeric Tax Code (ATC)</dt>
                        <dd class="text-lg font-medium text-gray-800">{{ $form->atc->tax_code ?? 'N/A' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-semibold text-gray-600">Tax Code</dt>
                        <dd class="text-lg font-medium text-gray-800">{{ $form->tax_code }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-semibold text-gray-600">Amount of Remittance</dt>
                        <dd class="text-lg font-medium text-gray-800">
                            ₱{{ number_format($form->amount_of_remittance, 2) }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-semibold text-gray-600">Remitted Previously</dt>
                        <dd class="text-lg font-medium text-gray-800">
                            ₱{{ number_format($form->remitted_previous, 2) }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-semibold text-gray-600">Net Amount of Remittance</dt>
                        <dd class="text-lg font-medium text-gray-800">
                            ₱{{ number_format($form->net_amount_of_remittance, 2) }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-semibold text-gray-600">Surcharge</dt>
                        <dd class="text-lg font-medium text-gray-800">
                            ₱{{ number_format($form->surcharge, 2) }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-semibold text-gray-600">Interest</dt>
                        <dd class="text-lg font-medium text-gray-800">
                            ₱{{ number_format($form->interest, 2) }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-semibold text-gray-600">Compromise</dt>
                        <dd class="text-lg font-medium text-gray-800">
                            ₱{{ number_format($form->compromise, 2) }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-semibold text-gray-600">Total Penalties</dt>
                        <dd class="text-lg font-medium text-gray-800">
                            ₱{{ number_format($form->total_penalties, 2) }}
                        </dd>
                    </div>

                    <div class="col-span-1 md:col-span-2 bg-gray-50 p-8 rounded-lg shadow-sm mt-8">
                        <dt class="text-lg font-semibold text-gray-700">Total Amount Due</dt>
                        <dd class="text-4xl font-extrabold text-indigo-700">
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
