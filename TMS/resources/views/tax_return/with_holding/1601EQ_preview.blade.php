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
                    <h1 class="text-4xl font-extrabold text-center text-gray-800">
                        1601EQ Form Preview
                    </h1>

                    <div class="space-x-4 flex items-center">
                        <a href="{{ route('form1601EQ.edit', ['id' => $form->id]) }}"
                            class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-5-8l6 6M15 4v3a1 1 0 001 1h3" />
                                </svg>
                            Edit
                        </a>

                        <a href="{{ route('form1601EQ.download', ['id' => $form->id]) }}" 
                            class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m4-4H8m8 0l-4 4m4-4l-4-4" />
                            </svg>
                            Download PDF
                        </a>
                    </div>
                </div>

                <!-- Background Information -->
                <h2 class="text-2xl font-bold mb-6">Background Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Withholding Agent</label>
                        <p class="mt-1 text-lg font-semibold">{{ $form->organization->registration_name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">TIN</label>
                        <p class="mt-1 text-lg font-semibold">{{ $form->organization->tin }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">RDO Code</label>
                        <p class="mt-1 text-lg font-semibold">{{ $form->organization->rdo }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Registered Address</label>
                        <p class="mt-1 text-lg font-semibold">{{ $form->organization->address_line }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Zip Code</label>
                        <p class="mt-1 text-lg font-semibold">{{ $form->organization->zip_code }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Contact Number</label>
                        <p class="mt-1 text-lg font-semibold">{{ $form->organization->contact_number }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Category of Withholding Agent</label>
                        <p class="mt-1 text-lg font-semibold">
                            {{ $form->organization->type === 'Private' ? 'Private' : 'Government' }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Email</label>
                        <p class="mt-1 text-lg font-semibold">{{ $form->organization->email }}</p>
                    </div>
                </div>

                <!-- Filing Details -->
                <h2 class="text-2xl font-bold mt-12 mb-6">Filing Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Year</label>
                        <p class="mt-1 text-lg font-semibold">{{ $form->year }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Quarter</label>
                        <p class="mt-1 text-lg font-semibold">{{ $form->getQuarterText() }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Amended Return</label>
                        <p class="mt-1 text-lg font-semibold">{{ $form->amended_return ? 'Yes' : 'No' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Any Taxes Withheld</label>
                        <p class="mt-1 text-lg font-semibold">{{ $form->any_taxes_withheld ? 'Yes' : 'No' }}</p>
                    </div>
                </div>

                <!-- Tax Computation -->
                <h2 class="text-2xl font-bold mt-12 mb-6">Tax Computation</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Total Taxes Withheld</label>
                        <p class="mt-1 text-lg font-semibold">PHP {{ number_format($form->total_taxes_withheld, 2) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Total Remittances Made</label>
                        <p class="mt-1 text-lg font-semibold">PHP {{ number_format($form->total_remittances_made, 2) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Tax Still Due</label>
                        <p class="mt-1 text-lg font-semibold">PHP {{ number_format($form->tax_still_due, 2) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Total Penalties</label>
                        <p class="mt-1 text-lg font-semibold">PHP {{ number_format($form->penalties, 2) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Total Amount Due</label>
                        <p class="mt-1 text-lg font-semibold text-red-600">PHP {{ number_format($form->total_amount_due, 2) }}</p>
                    </div>
                </div>

                <!-- ATC Details -->
                <h2 class="text-2xl font-bold mt-12 mb-6">ATC Details</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-2 px-4 border">ATC</th>
                                <th class="py-2 px-4 border">Description</th>
                                <th class="py-2 px-4 border">Tax Base</th>
                                <th class="py-2 px-4 border">Tax Rate</th>
                                <th class="py-2 px-4 border">Tax Withheld</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($form->atcDetails as $detail)
                                <tr>
                                    <td class="py-2 px-4 border">{{ $detail->atc->tax_code }}</td>
                                    <td class="py-2 px-4 border">{{ $detail->atc->description }}</td>
                                    <td class="py-2 px-4 border">PHP {{ number_format($detail->tax_base, 2) }}</td>
                                    <td class="py-2 px-4 border">{{ $detail->tax_rate }}%</td>
                                    <td class="py-2 px-4 border">PHP {{ number_format($detail->tax_withheld, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
            