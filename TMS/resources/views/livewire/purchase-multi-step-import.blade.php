<div>
    <!-- Modal Trigger Button -->
    <button wire:click="openModalPurchase" class="w-full text-left block px-4 py-2 hover-dropdown">
        Import CSV
    </button>

    <!-- Modal Content -->
    @if($modalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-2xl">
                <!-- Modal Header -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Multi-Step Import</h3>
                    <button wire:click="closeModal" class="text-gray-500">X</button>
                </div>

                @if(session()->has('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session()->has('message'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('message') }}
                    </div>
                @endif

                <!-- Step 1: File Upload -->
                @if($step === 1)
                    <div>
                        <h4 class="text-xl font-semibold mb-2">Step 1: Select a File</h4>
                        <p class="mb-4">Please upload an Excel file for import.</p>
                        <a class="mb-4" href="{{ url('/export-coa') }}">
                        To get a list of the Chart of Accounts Click Here <br>
                        </a>
                        <a class="mb-4" href="{{ url('/export-atc/purchase') }}">
                          To get a list of the ATCs for purchase transactions click here <br>
                        </a>
                        <a class="mb-4" href="{{ url('/export-tax-type/purchase') }}">
                            To get a list of the Tax Types for purchase transactions click here
                          </a>
                        
                        <input type="file" wire:model="file" class="border p-2 w-full" accept=".xlsx,.xls,.csv" />
                        <div wire:loading wire:target="file">
                            Uploading and processing file...
                        </div>
                        @error('file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        
                        <div class="mt-4 flex justify-end">
                            <button wire:click="closeModal" class="btn bg-gray-500 text-white p-2 rounded mr-2">Cancel</button>
                        </div>
                    </div>
                @endif

                <!-- Step 2: Column Mapping -->
                @if($step === 2 && $columns)
                    <div>
                        <h4 class="text-xl font-semibold mb-2">Step 2: Column Mapping</h4>
                        <p class="mb-4">Map the columns of your file to the corresponding database fields.</p>
                        
                        <form wire:submit.prevent="mapColumns">
                            <div class="grid grid-cols-2 gap-8">
                                @php
                                    $databaseColumns = [
                                        'date' => 'Date',
                                        'contact_tin' => 'Contact TIN',
                                        'contact_name' => 'Contact Name',
                                        'last_name' => 'Last Name',
                                        'first_name' => 'First Name',
                                        'middle_name' => 'Middle Name',
                                        'address_line' => 'Address Line',
                                        'city' => 'City',
                                        'zip_code' => 'Zip Code',
                                        'reference_no' => 'Reference No.',
                                        'description' => 'Description',
                                        'category' => 'Category',
                                        'tax_type' => 'Tax Type',
                                        'atc' => 'ATC',
                                        'coa_code' => 'COA Code',
                                        'amount' => 'Amount',
                                    ];
                                    $halfCount = ceil(count($databaseColumns) / 2);
                                @endphp

                                <!-- Left Column -->
                                <div>
                                    @foreach(array_slice($databaseColumns, 0, $halfCount) as $field => $label)
                                        <div class="mb-4">
                                            <label for="mappedColumns.{{ $field }}" class="block mb-2">{{ $label }}</label>
                                            <select wire:model="mappedColumns.{{ $field }}" 
                                                    id="mappedColumns.{{ $field }}" 
                                                    class="border p-2 w-full rounded">
                                                <option value="">Select Column</option>
                                                @foreach($columns as $column)
                                                    <option value="{{ $column }}">{{ $column }}</option>
                                                @endforeach
                                            </select>
                                            @error("mappedColumns.$field") 
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Right Column -->
                                <div>
                                    @foreach(array_slice($databaseColumns, $halfCount) as $field => $label)
                                        <div class="mb-4">
                                            <label for="mappedColumns.{{ $field }}" class="block mb-2">{{ $label }}</label>
                                            <select wire:model="mappedColumns.{{ $field }}" 
                                                    id="mappedColumns.{{ $field }}" 
                                                    class="border p-2 w-full rounded">
                                                <option value="">Select Column</option>
                                                @foreach($columns as $column)
                                                    <option value="{{ $column }}">{{ $column }}</option>
                                                @endforeach
                                            </select>
                                            @error("mappedColumns.$field") 
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-4 flex justify-end space-x-2">
                                <button type="button" wire:click="$set('step', 1)" class="btn bg-gray-500 text-white p-2 rounded">
                                    Back
                                </button>
                                <button type="submit" class="btn bg-blue-500 text-white p-2 rounded">
                                    Next
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- Step 3: Review Import -->
                @if($step === 3 && $previewData)
                    <div>
                        <h4 class="text-xl font-semibold mb-2">Step 3: Review Import</h4>
                        <p class="mb-4">Please review the first 5 rows of data before confirming the import.</p>

                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto border-collapse border">
                                <thead>
                                    <tr>
                                        @foreach($mappedColumns as $field => $column)
                                            <th class="border p-2 bg-gray-100">{{ $databaseColumns[$field] }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($previewData as $row)
                                        <tr>
                                            @foreach($mappedColumns as $field => $column)
                                                <td class="border p-2">
                                                    {{ $row[$field] ?? 'N/A' }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 flex justify-end space-x-2">
                            <button wire:click="$set('step', 2)" class="btn bg-gray-500 text-white p-2 rounded">
                                Back
                            </button>
                            <button wire:click="saveImport" class="btn bg-green-500 text-white p-2 rounded">
                                Confirm Import
                            </button>
                        </div>\
                    </div>
                @endif

                <!-- Step 4: Success Message -->
                @if($step === 4)
                    <div class="text-center">
                        <h4 class="text-xl font-semibold mb-2">Import Successful!</h4>
                        <p class="mb-4">Your data has been successfully imported.</p>
                        <button wire:click="closeModal" class="btn bg-gray-500 text-white p-2 rounded">
                            Close
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>