<x-app-layout>
    <div class="max-w-6xl mx-auto py-12 px-6">
        <div class="relative flex items-center mb-6">
            <button onclick="window.location.href='{{ route('with_holding.1604E') }}'" class="flex items-center text-zinc-600 hover:text-zinc-800 transition duration-150">
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
                        <p class="text-sm taxuri-color">BIR Form No. 1604-E Preview</p>
                        <p class="font-bold text-2xl taxuri-color">Annual Information Return <span class="text-sm">(of Creditable Income Taxes Withheld (Expanded))</span></p>
                    </div>
                    @if($with_holding->status !== 'Filed')
                        <div class="flex space-x-2 items-center">
                            <!-- Edit Button -->
                            <a href="{{ route('form1601EQ.edit', ['id' => $with_holding->id]) }}" 
                            class="border px-6 py-2 text-sm text-zinc-600 rounded-lg hover:border-blue-500 hover:text-blue-500 hover:bg-blue-100 transition flex items-center group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-gray-600 group-hover:text-blue-500" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M21 12a1 1 0 0 0-1 1v6a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h6a1 1 0 0 0 0-2H5a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3v-6a1 1 0 0 0-1-1m-15 .76V17a1 1 0 0 0 1 1h4.24a1 1 0 0 0 .71-.29l6.92-6.93L21.71 8a1 1 0 0 0 0-1.42l-4.24-4.29a1 1 0 0 0-1.42 0l-2.82 2.83l-6.94 6.93a1 1 0 0 0-.29.71m10.76-8.35l2.83 2.83l-1.42 1.42l-2.83-2.83ZM8 13.17l5.93-5.93l2.83 2.83L10.83 16H8Z"/>
                                </svg>
                                Edit
                            </a>
                            <div x-data="{ 
                                    showConfirmFileModal: false, 
                                    showSuccessFileModal: false,
                                    fileReport() {
                                        fetch('{{ route('form1604E.markFiled', ['id' => $with_holding->id]) }}', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                        })
                                        .then(response => {
                                            if (response.ok) {
                                                this.showSuccessFileModal = true; // Show success modal
                                                setTimeout(() => {
                                                    location.reload(); // Reload after 700ms
                                                }, 700);
                                            } else {
                                                alert('Error filing the report. Please try again.');
                                            }
                                        })
                                        .catch(error => {   
                                            console.error('Error:', error.message);
                                            alert(error.message || 'An error occurred. Please try again.');
                                        });

                                    }
                                }"
                                class=" my-6 mx-12 overflow-hidden max-w-full"
                            >
                                <!-- Mark as Filed Button -->
                                <button 
                                    type="button" 
                                    @click="showConfirmFileModal = true" 
                                    class="border px-3 py-2 text-sm text-zinc-600 rounded-lg hover:border-green-500 hover:text-green-500 hover:bg-green-100 transition flex items-center group"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-gray-600 group-hover:text-green-500" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2M7 11l5 5l5-5m-5-7v12"/>
                                    </svg>
                                    File Report
                                </button>

                                <!-- File Confirmation Modal -->
                                <div 
                                    x-show="showConfirmFileModal" 
                                    x-cloak 
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-200 bg-opacity-50"
                                    @click.away="showConfirmFileModal = false"
                                    x-effect="document.body.classList.toggle('overflow-hidden', showConfirmFileModal)"
                                    >
                                    <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full relative">
                                        <button @click="showConfirmFileModal = false" class="absolute top-4 right-4 bg-gray-200 hover:bg-gray-400 text-white rounded-full p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-3 h-3">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-exclamation-triangle text-yellow-500 text-8xl mb-4"></i>
                                            <h2 class="text-2xl font-bold text-zinc-700 mb-2">FILE REPORT</h2>
                                            <p class="text-sm text-zinc-700 text-center">
                                                You're about to file the report. Are you sure?
                                            </p>
                                            <div class="flex justify-center space-x-8 mt-6 w-full">
                                                <button 
                                                    @click="showConfirmFileModal = false" 
                                                    class="px-4 py-2 rounded-lg text-sm text-zinc-700 font-bold transition"
                                                >
                                                    Cancel
                                                </button>
                                                <button 
                                                    @click="fileReport(); showConfirmFileModal = false;" 
                                                    class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm transition"
                                                >
                                                    File
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Success File Modal -->
                                <div 
                                    x-show="showSuccessFileModal" 
                                    x-cloak 
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-200 bg-opacity-50"
                                    x-effect="document.body.classList.toggle('overflow-hidden', showSuccessFileModal)"
                                    @click.away="showSuccessFileModal = false"
                                    >
                                    <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-check-circle text-green-500 text-6xl mb-4"></i>
                                            <h2 class="text-2xl font-bold text-zinc-700 mb-2">File Report Successful!</h2>
                                            <p class="text-sm text-zinc-700 text-center">
                                                The report is now downloadable.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endif
                    
                    @if($with_holding->status === 'Filed')
                        <div class="flex space-x-2 items-center">
                            <!-- Filed Indicator -->
                            <div class="flex flex-col space-y-1 px-4 py-2 w-auto bg-green-100 border border-green-500 rounded-lg">
                                <div class="flex items-center space-x-2 text-sm text-green-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M9 16.2l-4.2-4.2L3.4 13.4l5.6 5.6L20.6 7.4l-1.4-1.4L9 16.2z" />
                                    </svg>
                                    <span>Filed:</span> 
                                    <span>{{ $with_holding->updated_at->format('F j, Y, g:i A') }}</span>
                                </div>
                            </div>
                            <!-- Download PDF Button -->
                            <a href="{{ route('form1601EQ.download', ['id' => $form1604E->id]) }}" 
                            class="border px-3 py-2 text-sm text-zinc-600 rounded-lg hover:border-green-500 hover:text-green-500 hover:bg-green-100 transition flex items-center group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-gray-600 group-hover:text-green-500" viewBox="0 0 24 24">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2M7 11l5 5l5-5m-5-7v12"/>
                                </svg>
                                Download PDF
                            </a>
                        </div>
                    @endif
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

        <div class="bg-white shadow-sm rounded-lg mt-6 overflow-hidden">
            <div class="px-8 py-10">
                <!-- Background Information -->
                <h3 class="font-bold text-zinc-700 text-lg mb-4">Background Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="text-sm font-bold text-zinc-600">Withholding Agent</label>
                        <p class="text-sm text-zinc-800">{{ $with_holding->organization->registration_name ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-bold text-zinc-600">Taxpayer Identification Number (TIN)</label>
                        <p class="text-sm text-zinc-800">{{ $with_holding->organization->tin ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-bold text-zinc-600">YEAR</label>
                        <p class="text-sm text-zinc-800">{{ $with_holding->year ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-bold text-zinc-600">RDO Code</label>
                        <p class="text-sm text-zinc-800">{{ $with_holding->organization->rdo ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-bold text-zinc-600">Registered Address</label>
                        <p class="text-sm text-zinc-800">{{ $with_holding->organization->address_line ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-bold text-zinc-600">Zip Code</label>
                        <p class="text-sm text-zinc-800">{{ $with_holding->organization->zip_code ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-bold text-zinc-600">Contact Number</label>
                        <p class="text-sm text-zinc-800">{{ $with_holding->organization->contact_number ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-bold text-zinc-600">Email</label>
                        <p class="text-sm text-zinc-800">{{ $with_holding->organization->email ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-bold text-zinc-600">Category of Withholding Agent</label>
                        <p class="text-sm text-zinc-800">
                            {{ optional($form1604E)->agent_category === 'Private' ? 'Private' : 'Government' }}
                        </p>
                    </div>

                     <div>
                        <label class="text-sm font-bold text-zinc-600">Amended Return?</label>
                        <p class="text-sm text-zinc-800">
                            {{ optional($form1604E)->amended_return == 1 ? 'Yes' : 'No' }}
                        </p>
                    </div>
                </div>

                <h3 class="font-bold text-zinc-700 text-lg mt-4 mb-4">Part II - Summary of Remittances</h3>
                <div class="overflow-x-auto">
                    <table class="w-full items-start text-left text-sm text-neutral-600 p-4">
                        <thead class="bg-neutral-100 text-sm text-neutral-700">
                            <tr>
                                <th class="py-4 px-4">Quarter</th>
                                <th class="py-4 px-4">Remittance Date</th>
                                <th class="py-4 px-4">Taxes Withheld</th>
                                <th class="py-4 px-4">Penalties</th>
                                <th class="py-4 px-4">Total Remitted</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-300 text-left py-[7px]">
                            @foreach($quarters as $quarter)
                                <tr>
                                    <td class="py-4 px-4">{{ $quarter['name'] }}</td>
                                    <td class="py-4 px-4">{{ $quarter['remittance_date'] }}</td>
                                    <td class="py-4 px-4">PHP {{ number_format($quarter['taxes_withheld'], 2) }}</td>
                                    <td class="py-4 px-4">PHP {{ number_format($quarter['penalties'], 2) }}</td>
                                    <td class="py-4 px-4">PHP {{ number_format($quarter['total_remitted'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>Total</th>
                                <th class="py-4 px-4">PHP {{ number_format($totalTaxesWithheld, 2) }}</th>
                                <th class="py-4 px-4">PHP {{ number_format($totalPenalties, 2) }}</th>
                                <th class="py-4 px-4">PHP {{ number_format($totalRemitted, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
