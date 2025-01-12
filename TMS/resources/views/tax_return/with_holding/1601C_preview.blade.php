<x-app-layout>
    <div class="py-6 flex justify-center">
        <div class="max-w-4xl w-full bg-white shadow-md rounded-lg">
            <div class="p-8">
                <!-- Title -->
                <h1 class="text-3xl font-bold text-blue-900 text-center mb-8">Preview: Form 1601C</h1>

                <!-- Withholding Details -->
                <div class="mb-8">
                    <h2 class="font-bold text-lg text-gray-700 mb-4 border-b-2 pb-2">Withholding Details</h2>
                    <div class="grid grid-cols-2 gap-6">
                        <p><strong>Filing Period:</strong> {{ $form->filing_period }}</p>
                        <p><strong>Amended Return:</strong> {{ $form->amended_return ? 'Yes' : 'No' }}</p>
                        <p><strong>Any Taxes Withheld:</strong> {{ $form->any_taxes_withheld ? 'Yes' : 'No' }}</p>
                        <p><strong>Number of Sheets Attached:</strong> {{ $form->number_of_sheets }}</p>
                    </div>
                </div>

                <!-- Organization Information -->
                <div class="mb-8">
                    <h2 class="font-bold text-lg text-gray-700 mb-4 border-b-2 pb-2">Background Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Withholding Agent</label>
                            <p class="mt-1 text-lg font-semibold">{{ $form->organization->registration_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">TIN</label>
                            <p class="mt-1 text-lg font-semibold">{{ $form->organization->tin ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Year</label>
                            <p class="mt-1 text-lg font-semibold">{{ $form->year ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">RDO Code</label>
                            <p class="mt-1 text-lg font-semibold">{{ $form->organization->rdo ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Registered Address</label>
                            <p class="mt-1 text-lg font-semibold">{{ $form->organization->address_line ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Zip Code</label>
                            <p class="mt-1 text-lg font-semibold">{{ $form->organization->zip_code ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Contact Number</label>
                            <p class="mt-1 text-lg font-semibold">{{ $form->organization->contact_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Email</label>
                            <p class="mt-1 text-lg font-semibold">{{ $form->organization->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Agent Type</label>
                            <p class="mt-1 text-lg font-semibold">{{ $form->organization->type ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- ATC Details -->
                <div class="mb-8">
                    <h2 class="font-bold text-lg text-gray-700 mb-4 border-b-2 pb-2">ATC Details</h2>
                    <div class="grid grid-cols-2 gap-6">
                        <p><strong>Tax Code:</strong> {{ $form->atc->tax_code }}</p>
                        <p><strong>Description:</strong> {{ $form->atc->description }}</p>
                    </div>
                </div>

                <!-- Computation Summary -->
                <div class="mb-8">
                    <h2 class="font-bold text-lg text-gray-700 mb-4 border-b-2 pb-2">Computation Summary</h2>
                    <div class="grid grid-cols-2 gap-6">
                        <p><strong>Total Compensation:</strong> ₱{{ number_format($form->total_compensation, 2) }}</p>
                        <p><strong>Taxable Compensation:</strong> ₱{{ number_format($form->taxable_compensation, 2) }}</p>
                        <p><strong>Tax Due:</strong> ₱{{ number_format($form->tax_due, 2) }}</p>
                        <p><strong>Other Remittances:</strong> ₱{{ number_format($form->other_remittances, 2) }}</p>
                        <p><strong>Surcharge:</strong> ₱{{ number_format($form->surcharge, 2) }}</p>
                        <p><strong>Interest:</strong> ₱{{ number_format($form->interest, 2) }}</p>
                        <p><strong>Compromise:</strong> ₱{{ number_format($form->compromise, 2) }}</p>
                        <p class="font-bold text-red-600"><strong>Total Amount Due:</strong> ₱{{ number_format($form->total_amount_due, 2) }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex justify-center space-x-4">
                    <a href="{{ route('form1601C.download', ['id' => $form->id]) }}" class="bg-blue-900 text-white py-2 px-4 rounded hover:bg-blue-950">
                        Download PDF
                    </a>
                    <a href="{{ route('form1601C.edit', ['id' => $form->id]) }}" class="bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-600">
                        Edit Form
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
