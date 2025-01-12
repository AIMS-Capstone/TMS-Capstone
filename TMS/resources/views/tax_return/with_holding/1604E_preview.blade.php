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
                        1604E Form Preview
                    </h1>

                    <div class="space-x-4 flex items-center">
                        <a href="{{ route('form1604E.edit', ['id' => $with_holding->id]) }}"
                            class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-5-8l6 6M15 4v3a1 1 0 001 1h3" />
                                </svg>
                            Edit
                        </a>

                        <a href="{{ route('form1604E.download', ['id' => $form1604E->id]) }}" 
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
                        <p class="mt-1 text-lg font-semibold">{{ $with_holding->organization->registration_name ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">TIN</label>
                        <p class="mt-1 text-lg font-semibold">{{ $with_holding->organization->tin ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">YEAR</label>
                        <p class="mt-1 text-lg font-semibold">{{ $with_holding->year ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">RDO Code</label>
                        <p class="mt-1 text-lg font-semibold">{{ $with_holding->organization->rdo ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Registered Address</label>
                        <p class="mt-1 text-lg font-semibold">{{ $with_holding->organization->address_line ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Zip Code</label>
                        <p class="mt-1 text-lg font-semibold">{{ $with_holding->organization->zip_code ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Contact Number</label>
                        <p class="mt-1 text-lg font-semibold">{{ $with_holding->organization->contact_number ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Email</label>
                        <p class="mt-1 text-lg font-semibold">{{ $with_holding->organization->email ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Category of Withholding Agent</label>
                        <p class="mt-1 text-lg font-semibold">
                            {{ optional($form1604E)->agent_category === 'Private' ? 'Private' : 'Government' }}
                        </p>
                    </div>

                     <div>
                        <label class="block text-sm font-medium text-gray-600">Amended Return?</label>
                        <p class="mt-1 text-lg font-semibold">
                            {{ optional($form1604E)->amended_return == 1 ? 'Yes' : 'No' }}
                        </p>
                    </div>
                </div>

                <h2 class="text-2xl font-bold mt-12 mb-6">Part II - Summary of Remittances</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-2 px-4 border">Quarter</th>
                                <th class="py-2 px-4 border">Remittance Date</th>
                                <th class="py-2 px-4 border">Taxes Withheld</th>
                                <th class="py-2 px-4 border">Penalties</th>
                                <th class="py-2 px-4 border">Total Remitted</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quarters as $quarter)
                                <tr>
                                    <td class="py-2 px-4 border">{{ $quarter['name'] }}</td>
                                    <td class="py-2 px-4">{{ $quarter['remittance_date'] }}</td>
                                    <td class="py-2 px-4 border">PHP {{ number_format($quarter['taxes_withheld'], 2) }}</td>
                                    <td class="py-2 px-4 border">PHP {{ number_format($quarter['penalties'], 2) }}</td>
                                    <td class="py-2 px-4 border">PHP {{ number_format($quarter['total_remitted'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>Total</th>
                                <th class="py-2 px-4">PHP {{ number_format($totalTaxesWithheld, 2) }}</th>
                                <th class="py-2 px-4">PHP {{ number_format($totalPenalties, 2) }}</th>
                                <th class="py-2 px-4">PHP {{ number_format($totalRemitted, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
