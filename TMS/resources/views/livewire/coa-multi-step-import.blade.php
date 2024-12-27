<div>
    <!-- Modal Trigger Button -->
    <button 
        wire:click="openModalCoaImport" 
        class="border px-3 py-2 rounded-lg text-sm hover:border-green-500 hover:text-green-500 hover:bg-green-100 transition flex items-center space-x-1 group"
        >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 text-zinc-600 group-hover:text-green-500 transition">
            <path fill="currentColor" fill-rule="evenodd" d="M8 3.25A2.756 2.756 0 0 0 5.25 6v12A2.756 2.756 0 0 0 8 20.75h8A2.756 2.756 0 0 0 18.75 18V9.5a.75.75 0 0 0-.22-.53l-5.5-5.5a.75.75 0 0 0-.53-.22zM6.75 6c0-.686.564-1.25 1.25-1.25h3.75V9.5c0 .414.336.75.75.75h4.75V18c0 .686-.564 1.25-1.25 1.25H8c-.686 0-1.25-.564-1.25-1.25zm9.44 2.75l-2.94-2.94v2.94zM15.25 15a.75.75 0 0 1-.75.75h-1.75v1.75a.75.75 0 0 1-1.5 0v-1.75H9.5a.75.75 0 0 1 0-1.5h1.75V12.5a.75.75 0 0 1 1.5 0v1.75h1.75a.75.75 0 0 1 .75.75" clip-rule="evenodd"/>
        </svg>
        <span class="text-zinc-600 group-hover:text-green-500 transition">Import</span>
    </button>

    <!-- Modal Content -->
    @if($modalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-200 bg-opacity-50 overflow-hidden">
            <div class="bg-white rounded-lg w-full max-w-screen-lg max-h-[100%] mx-auto z-10 overflow-y-auto my-auto">
                <!-- Modal Header -->
                <div class="flex relative justify-between items-center mb-4 bg-blue-900 border-opacity-80 w-full p-3">
                    <div class="flex justify-center items-center bg-blue-900 border-opacity-80 w-full">
                        <h1 class="text-lg font-bold text-white">Chart of Accounts</h1>
                        <button 
                            wire:click="closeModal" 
                            class="absolute right-3 top-4 text-sm text-white hover:text-zinc-200 z-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <circle cx="12" cy="12" r="10" fill="white" class="transition duration-200 hover:fill-gray-300" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                    d="M8 8L16 16M8 16L16 8" 
                                    stroke="#1e3a8a" 
                                    class="transition duration-200 hover:stroke-gray-600" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Stepper Navigation -->
                <div class="flex justify-between items-center border-b mx-20 mb-6 mt-6 px-20">
                    @foreach([1 => 'Upload CSV File', 2 => 'Column Mapping', 3 => 'Review Import', 4 => 'Complete Import'] as $index => $label)
                        <div class="text-center w-1/4 relative">
                            <div class="flex justify-center items-center">
                                @if($step === $index)
                                    {{-- Current active step --}}
                                    <div class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-900 text-white border-2 border-blue-900">
                                        {{ $index }}
                                    </div>
                                @elseif($step > $index)
                                    <div class="flex items-center justify-center text-blue-900 font-bold">
                                        {{ $index }}
                                    </div>
                                @else
                                    <span class="text-blue-900">{{ $index }}</span>
                                @endif
                            </div>
                            <div class="mt-4 text-sm {{ $step === $index ? 'text-blue-900 font-extrabold' : 'text-gray-500' }}">
                                <span class="{{ $step === $index ? 'mb-4 inline-block font-extrabold' : '' }}">
                                    {{ $label }}
                                </span>
                                @if($step === $index)
                                    <div class="mt-4 absolute bottom-0 mx-5 left-0 align-center right-0 border-b-4 border-blue-900 rounded-b-md transform rotate-180"></div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @if(session()->has('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- @if(session()->has('message'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('message') }}
                    </div>
                @endif --}}

                <!-- Step 1: File Upload -->
                @if($step === 1)
                    {{-- <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="file">
                            Upload CSV File
                        </label>
                        <input type="file" wire:model="file" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('file') <span class="text-red-500">{{ $message }}</span> @enderror
                        <button wire:click="processFileUpload" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mt-4 rounded">
                            Next
                        </button>
                    </div> --}}
                    <div class="px-20">
                        <div class="flex flex-col space-y-4 justify-center items-center h-full">
                            <div class="p-6 h-full max-h-screen-md w-full max-w-screen-lg border rounded-sm flex flex-col">
                                <div class="flex flex-col text-center justify-center items-center text-blue-900 font-bold">
                                    <p class="text-lg">Importing</p>
                                    <p class="text-xl">Chart of Accounts from a CSV File</p>
                                    <p class="text-xs font-light text-zinc-600">By default, any CSV file can be imported by mapping the required fields<br>against the columns in your file.</p>
                                </div>
                        
                                <div class="text-xs text-zinc-700 mt-6">
                                    <p class="flex items-center pl-24 font-semibold">Preparing your CSV Files</p>
                                    <p class="flex items-center pl-24 text-xs mb-4">You will need:</p>
                                    <div class="flex justify-center mb-4 pl-10">
                                        <ul class="list-disc text-[11px]">
                                            <li>
                                                <span class="inline-block align-top">a CSV file containing transaction information (You can <a class="text-blue-600" href="{{ url('') }}">download</a> this template for importing transactions), or</span>
                                            </li>
                                            <li>
                                                <span class="inline-block align-top">Make sure to enter a valid <b>Account Type, </b> (<a class="text-blue-600" href="{{ url('') }}">download</a> list).</span>
                                            </li>
                                            <li>
                                                <span class="inline-block align-top">Fill Sub Category if the Account Type is <b>Ordinary Allowance Itemized Deductions, Assets,</b> or <b>Liabilities</b> (<a class="text-blue-600" href="{{ url('') }}">download</a> list).</span>
                                            </li>
                                            <li>
                                                <span class="inline-block align-top">For <b>Code</b>, it must be <b>unique.</b></span>
                                            </li>
                                            <li>
                                                <span class="inline-block align-top"><b>Name</b> is <b>required.</b></span>
                                            </li>
                                            <li>
                                                <span class="inline-block align-top"><b>Description</b> is <b>optional.</b></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full justify-start">
                            <label class="block font-bold mt-4 text-sm text-zinc-700 mb-2">Choose a file to import.</label>
                            <div class="flex items-center space-x-4">
                                <label class="cursor-pointer">
                                    <span class="px-4 py-2 border border-blue-900 rounded-md text-center shadow-sm text-xs font-bold text-blue-900 hover:bg-blue-900 hover:text-white">
                                        Browse
                                    </span>
                                    <input type="file" wire:model="file" class="hidden border p-2 w-full" accept=".xlsx,.xls,.csv" />
                                </label>
                                <span id="file-name" class="text-xs pl-2 text-zinc-500">No file selected</span>
                            </div>
                            
                            <p class="mt-2 text-xs text-gray-400">The file you import must be a CSV (Comma Separated Values) file.</p>
                            <div wire:loading wire:target="file" class="text-sm">
                                Uploading and processing file...
                            </div>
                            @error('file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-8 flex justify-end">
                            <button wire:click="closeModal" class="px-4 py-1 text-zinc-600 hover:text-zinc-900 hover:font-bold mr-2">Cancel</button>
                            <button wire:click="processFileUpload" class="bg-blue-900 hover:bg-blue-950 text-white font-semibold py-1.5 px-8 rounded-md">
                                Next
                            </button>
                        </div>   
                    </div>
                @endif

                <!-- Step 2: Column Mapping -->
                @if($step === 2)
                    <div class="px-20">                        
                        <form wire:submit.prevent="mapColumns" class="flex flex-col grow">
                            <div class="flex flex-col space-y-2 justify-center items-center">
                                <div class="p-6 h-full max-h-screen-md w-full max-w-screen-lg border rounded-sm flex flex-col">
                                    <div class="flex flex-col text-center justify-center items-center text-blue-900 font-bold">
                                        <p class="text-xl">Column Mapping</p>
                                        <p class="text-xs font-light text-zinc-600">Map the columns in your CSV file to the required transaction fields (*).</p>
                                    </div>
                                    <div class="flex justify-center items-center">
                                        <div class="w-full max-w-md">
                                            <div class="mt-4">
                                                @foreach($databaseColumns as $dbField => $label)
                                                    <div class="mb-2 flex items-center">
                                                        <label class="block font-semibold text-sm text-gray-700 w-1/3">{{ $label }}<span class="text-red-500">*</span></label>
                                                        <select 
                                                            wire:model="mappedColumns.{{ $dbField }}" 
                                                            class="block w-2/3 py-2 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                                            <option value="">Select Column</option>
                                                            @foreach($columns as $column)
                                                                <option value="{{ $column }}">{{ $column }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('mappedColumns.*') <span class="text-red-500">{{ $message }}</span> @enderror
                            <div class="flex w-full justify-between mt-4 mb-6">
                                <button type="button" wire:click="$set('step', 1)" class="font-bold border border-blue-900 text-blue-900 hover:bg-blue-900 hover:text-white px-6 py-1.5 rounded-md">
                                    Previous
                                </button>
                                <button type="submit" wire:click="mapColumns" class="font-bold bg-blue-900 text-white px-6 py-1.5 hover:bg-blue-950 rounded-md">
                                    Next
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- Step 3: Review Import -->
                @if($step === 3)
                    {{-- <div>
                        <h4 class="text-md font-semibold mb-4">Review Data</h4>
                        <table class="min-w-full bg-white mb-4">
                            <thead>
                                <tr>
                                    @foreach($databaseColumns as $label)
                                        <th class="py-2 px-4 border-b border-gray-200">{{ $label }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($previewData as $row)
                                    <tr>
                                        @foreach($databaseColumns as $dbField => $label)
                                            <td class="py-2 px-4 border-b border-gray-200">{{ $row[$dbField] ?? '' }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button wire:click="saveImport" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mt-4 rounded">
                            Import Data
                        </button>
                    </div> --}}
                    <div class="px-20">
                        <div class="flex flex-col space-y-2 justify-center items-center">
                            <div class="p-6 h-full max-h-screen-md w-full max-w-screen-lg border rounded-sm flex flex-col">
                                <div class="flex flex-col text-center justify-center items-center text-blue-900 font-bold">
                                    <p class="text-xl">Review Import</p>
                                    <p class="text-xs font-light text-zinc-600">Please Review the details below. This will help you on what is needed to<br/>be changed/updated to move on to the importing process.</p>
                                </div>
                                <div class="text-xs text-zinc-700 mt-6">
                                    <p class="flex items-center pl-24">If you want to continue importing, please click the “Next” button, or if you want to import a new CSV file, please<br/>go back to step 1 and select a new CSV file. </p>
                                    <p class="flex items-center pl-24 font-bold italic mt-2 mb-4">Here is a preview of how the account(s) will be mapped:</p>
                                </div>
                                <div class="overflow-auto custom-scrollbar">
                                    <table class="min-w-full table-auto text-left text-xs text-neutral-600">
                                        <thead class="bg-neutral-100 text-sm text-neutral-700">
                                            <tr>
                                                @foreach($databaseColumns as $label)
                                                    <th class="text-left py-4 px-4">{{ $label }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-neutral-300 text-left text-xs py-[7px]">
                                            @foreach($previewData as $row)
                                                <tr>
                                                    @foreach($databaseColumns as $dbField => $label)
                                                        <td class="text-left py-3 px-4">{{ $row[$dbField] ?? '' }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="flex w-full justify-between mt-4 mb-6">
                            <button wire:click="$set('step', 2)" class="font-bold border border-blue-900 text-blue-900 hover:bg-blue-900 hover:text-white px-6 py-1.5 rounded-md">
                                Previous
                            </button>
                            <button wire:click="saveImport" class="font-bold bg-blue-900 text-white px-6 py-1.5 hover:bg-blue-950 rounded-md">
                                Next
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Step 4: Success Message -->
                @if($step === 4)
                    <div class="px-20">
                        <div class="p-10 text-center border border-emerald-500 rounded bg-emerald-100">
                            <div class="flex justify-center mb-4">
                                <img src="{{ asset('images/Success.png') }}" alt="Import Successful" class="w-28 h-28">
                            </div>
                            <p class="text-2xl text-emerald-500 font-bold ">Import Successful</p>
                            <p class="text-zinc-700">Your CSV with filename <strong>[FILENAME]</strong> was successfully imported.</p>
                        </div>

                        <div class="flex w-full justify-end mt-4 mb-6">
                            <button wire:click="closeModal" class="font-bold bg-blue-900 text-white px-6 py-1.5 hover:bg-blue-950 rounded-md">
                                Done
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
