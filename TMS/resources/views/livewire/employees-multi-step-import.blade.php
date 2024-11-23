<div>
    <button 
        wire:click="openModalEmployeesImport" 
        class="block px-4 py-2 hover-dropdown">
        <span class="text-zinc-600 group-hover:text-green-500 transition">Upload CSV</span>
    </button>

        <!-- Modal -->
        @if($modalOpen)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 overflow-y-auto">
                <div class="bg-white rounded-lg p-6 w-full max-w-2xl">
                    <!-- Modal Header -->
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Multi-Step Employee Import</h3>
                        <button wire:click="closeModal" class="text-gray-500">X</button>
                    </div>

                    <!-- Error & Message Notifications -->
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

                    <!-- Step 1: Upload File -->
                    @if($step === 1)
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="file">
                                Upload CSV File
                            </label>
                            <input 
                                type="file" 
                                wire:model="file" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            >
                            @error('file') <span class="text-red-500">{{ $message }}</span> @enderror
                            <button wire:click="processFileUpload" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mt-4 rounded">
                                Next
                            </button>
                        </div>
                    @endif

                    <!-- Step 2: Map Columns -->
                    @if($step === 2)
                        <div>
                            <h4 class="text-md font-semibold mb-4">Map CSV Columns to Database Fields</h4>
                            @foreach($databaseColumns as $dbField => $label)
                                <div class="mb-3">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">{{ $label }}</label>
                                    <select 
                                        wire:model="mappedColumns.{{ $dbField }}" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    >
                                        <option value="">Select Column</option>
                                        @foreach($columns as $column)
                                            <option value="{{ $column }}">{{ $column }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach
                            @error('mappedColumns.*') <span class="text-red-500">{{ $message }}</span> @enderror
                            <button wire:click="mapColumns" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mt-4 rounded">
                                Next
                            </button>
                        </div>
                    @endif

                    <!-- Step 3: Review Data -->
                    @if($step === 3)
                        <div>
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
                        </div>
                    @endif

                    <!-- Step 4: Success Message -->
                    @if($step === 4)
                        <div>
                            <h4 class="text-md font-semibold text-green-600 mb-4">Import Successful!</h4>
                            <p>Your CSV data has been successfully imported.</p>
                            <button wire:click="closeModal" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 mt-4 rounded">
                                Close
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @endif
</div>

<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('reloadPage', () => {
            window.location.reload();
        });
    });
</script>