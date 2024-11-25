<div>
    <!-- Modal Trigger Button -->
    <button wire:click="openModal" class="w-full text-left block px-4 py-2 hover-dropdown">
        Import CSV
    </button>

    <!-- Modal Content -->
    @if($modalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-900 bg-opacity-50">
            <div class="bg-white rounded-lg w-full max-w-screen-lg max-h-screen mx-auto h-auto z-10 overflow-hidden">
                <!-- Modal Header -->
                <div class="flex relative justify-between items-center mb-4 bg-blue-900 border-opacity-80 w-full p-3">
                    <div class="flex justify-center items-center bg-blue-900 border-opacity-80 w-full">
                        <h1 class="text-lg font-bold text-white">Sales Transaction Source</h1>
                    </div>
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

                
                {{-- Tab Stepper: Active-Done-Disabled --}}

                <!-- Step 1: File Upload -->
                @if($step === 1)
                    <div class="p-10">
                        <h4 class="text-xl font-semibold mb-2">Step 1: Select a File</h4>
           


                        <ol class="flex items-center w-full">
                            <li class="flex w-full items-center text-blue-600 dark:text-blue-500 after:content-[''] after:w-full after:h-1 after:border-b after:border-blue-100 after:border-4 after:inline-block dark:after:border-blue-800">
                                <span class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full lg:h-12 lg:w-12 dark:bg-blue-800 shrink-0">
                                    <svg class="w-3.5 h-3.5 text-blue-600 lg:w-4 lg:h-4 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                                    </svg>
                                </span>
                            </li>
                            <li class="flex w-full items-center after:content-[''] after:w-full after:h-1 after:border-b after:border-gray-100 after:border-4 after:inline-block dark:after:border-gray-700">
                                <span class="flex items-center justify-center w-10 h-10 bg-gray-100 rounded-full lg:h-12 lg:w-12 dark:bg-gray-700 shrink-0">
                                    <svg class="w-4 h-4 text-gray-500 lg:w-5 lg:h-5 dark:text-gray-100" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 16">
                                        <path d="M18 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2ZM6.5 3a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5ZM3.014 13.021l.157-.625A3.427 3.427 0 0 1 6.5 9.571a3.426 3.426 0 0 1 3.322 2.805l.159.622-6.967.023ZM16 12h-3a1 1 0 0 1 0-2h3a1 1 0 0 1 0 2Zm0-3h-3a1 1 0 1 1 0-2h3a1 1 0 1 1 0 2Zm0-3h-3a1 1 0 1 1 0-2h3a1 1 0 1 1 0 2Z"/>
                                    </svg>
                                </span>
                            </li>
                            <li class="flex items-center w-full">
                                <span class="flex items-center justify-center w-10 h-10 bg-gray-100 rounded-full lg:h-12 lg:w-12 dark:bg-gray-700 shrink-0">
                                    <svg class="w-4 h-4 text-gray-500 lg:w-5 lg:h-5 dark:text-gray-100" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                                        <path d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2ZM7 2h4v3H7V2Zm5.7 8.289-3.975 3.857a1 1 0 0 1-1.393 0L5.3 12.182a1.002 1.002 0 1 1 1.4-1.436l1.328 1.289 3.28-3.181a1 1 0 1 1 1.392 1.435Z"/>
                                    </svg>
                                </span>
                            </li>
                        </ol>
                        

                        
                        </div>
                        <p class="mb-4">Please upload an Excel file for import.</p>
                        <input type="file" wire:model="file" class="border p-2 w-full" accept=".xlsx,.xls,.csv" />
                        <div wire:loading wire:target="file">
                            Uploading and processing file...
                        </div>
                        @error('file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        
                        <div class="mt-4 flex justify-end">
                            <button wire:click="closeModal" class="btn bg-zinc-500 text-white p-2 rounded mr-2">Cancel</button>
                        </div>                        
                    </div>
                @endif

                <!-- Step 2: Column Mapping -->
                @if($step === 2 && $columns)
                    <div>
                        <h4 class="text-xl font-semibold mb-2">Step 2: Column Mapping</h4>
                        <p class="mb-4">Map the columns of your file to the corresponding database fields.</p>
                        <ol class="flex items-center w-full">
                            <!-- Step 1: Completed -->
                            <li class="flex w-full items-center text-blue-600 dark:text-blue-500 after:content-[''] after:w-full after:h-1 after:border-b after:border-blue-100 after:border-4 after:inline-block dark:after:border-blue-800">
                                <span class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full lg:h-12 lg:w-12 dark:bg-blue-800 shrink-0">
                                    <svg class="w-3.5 h-3.5 text-blue-600 lg:w-4 lg:h-4 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                                    </svg>
                                </span>
                            </li>
                        
                            <!-- Step 2: Active -->
                            <li class="flex w-full items-center text-blue-600 dark:text-blue-500 after:content-[''] after:w-full after:h-1 after:border-b after:border-gray-100 after:border-4 after:inline-block dark:after:border-gray-700">
                                <span class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full lg:h-12 lg:w-12 dark:bg-blue-800 shrink-0">
                                    <svg class="w-4 h-4 text-blue-600 lg:w-5 lg:h-5 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 16">
                                        <path d="M18 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2ZM6.5 3a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5ZM3.014 13.021l.157-.625A3.427 3.427 0 0 1 6.5 9.571a3.426 3.426 0 0 1 3.322 2.805l.159.622-6.967.023ZM16 12h-3a1 1 0 0 1 0-2h3a1 1 0 0 1 0 2Zm0-3h-3a1 1 0 1 1 0-2h3a1 1 0 1 1 0 2Zm0-3h-3a1 1 0 1 1 0-2h3a1 1 0 1 1 0 2Z"/>
                                    </svg>
                                </span>
                            </li>
                        
                            <!-- Step 3: Inactive -->
                            <li class="flex items-center w-full">
                                <span class="flex items-center justify-center w-10 h-10 bg-gray-100 rounded-full lg:h-12 lg:w-12 dark:bg-gray-700 shrink-0">
                                    <svg class="w-4 h-4 text-gray-500 lg:w-5 lg:h-5 dark:text-gray-100" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                                        <path d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2ZM7 2h4v3H7V2Zm5.7 8.289-3.975 3.857a1 1 0 0 1-1.393 0L5.3 12.182a1.002 1.002 0 1 1 1.4-1.436l1.328 1.289 3.28-3.181a1 1 0 1 1 1.392 1.435Z"/>
                                    </svg>
                                </span>
                            </li>
                        </ol>
                        
                        
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
                                        'invoice_no' => 'Invoice No.',
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
                                <button type="button" wire:click="$set('step', 1)" class="btn bg-zinc-500 text-white p-2 rounded">
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
                        <ol class="flex items-center w-full">
                            <!-- Step 1: Completed -->
                            <li class="flex w-full items-center text-blue-600 dark:text-blue-500 after:content-[''] after:w-full after:h-1 after:border-b after:border-blue-100 after:border-4 after:inline-block dark:after:border-blue-800">
                                <span class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full lg:h-12 lg:w-12 dark:bg-blue-800 shrink-0">
                                    <svg class="w-3.5 h-3.5 text-blue-600 lg:w-4 lg:h-4 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                                    </svg>
                                </span>
                            </li>
                        
                            <!-- Step 2: Completed -->
                            <li class="flex w-full items-center text-blue-600 dark:text-blue-500 after:content-[''] after:w-full after:h-1 after:border-b after:border-blue-100 after:border-4 after:inline-block dark:after:border-blue-800">
                                <span class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full lg:h-12 lg:w-12 dark:bg-blue-800 shrink-0">
                                    <svg class="w-4 h-4 text-blue-600 lg:w-5 lg:h-5 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 16">
                                        <path d="M18 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2ZM6.5 3a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5ZM3.014 13.021l.157-.625A3.427 3.427 0 0 1 6.5 9.571a3.426 3.426 0 0 1 3.322 2.805l.159.622-6.967.023ZM16 12h-3a1 1 0 0 1 0-2h3a1 1 0 0 1 0 2Zm0-3h-3a1 1 0 1 1 0-2h3a1 1 0 1 1 0 2Zm0-3h-3a1 1 0 1 1 0-2h3a1 1 0 1 1 0 2Z"/>
                                    </svg>
                                </span>
                            </li>
                        
                            <!-- Step 3: Active -->
                            <li class="flex items-center w-full">
                                <span class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full lg:h-12 lg:w-12 dark:bg-blue-800 shrink-0">
                                    <svg class="w-4 h-4 text-blue-600 lg:w-5 lg:h-5 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                                        <path d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2ZM7 2h4v3H7V2Zm5.7 8.289-3.975 3.857a1 1 0 0 1-1.393 0L5.3 12.182a1.002 1.002 0 1 1 1.4-1.436l1.328 1.289 3.28-3.181a1 1 0 1 1 1.392 1.435Z"/>
                                    </svg>
                                </span>
                            </li>
                        </ol>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto border-collapse border">
                                <thead>
                                    <tr>
                                        @foreach($mappedColumns as $field => $column)
                                            <th class="border p-2 bg-zinc-100">{{ $databaseColumns[$field] }}</th>
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
                            <button wire:click="$set('step', 2)" class="btn bg-zinc-500 text-white p-2 rounded">
                                Back
                            </button>
                            <button wire:click="saveImport" class="btn bg-green-500 text-white p-2 rounded">
                                Confirm Import
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Step 4: Success Message -->
                @if($step === 4)
                    <div class="text-center">
                        <h4 class="text-xl font-semibold mb-2">Import Successful!</h4>
                        <p class="mb-4">Your data has been successfully imported.</p>
                        <button wire:click="closeModal" class="btn bg-zinc-500 text-white p-2 rounded">
                            Close
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
