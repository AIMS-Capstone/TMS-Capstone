@php
$organizationId = session('organization_id');
@endphp
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Page Main -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h1 class="text-lg font-semibold text-gray-700">Withholding Tax Return</h1>
                </div>

                <!-- Third Header -->
                <div x-data="{
                    showCheckboxes: false,
                    checkAll: false,
                    selectedRows: [],
                    showDeleteCancelButtons: false,

                    // Toggle a single row
                    toggleCheckbox(id) {
                        if (this.selectedRows.includes(id)) {
                            this.selectedRows = this.selectedRows.filter(rowId => rowId !== id);
                        } else {
                        this.selectedRows.push(id);
                        }
                        console.log(this.selectedRows); // Debugging line
                    },
                                        
                    // Toggle all rows
                    toggleAll() {
                    this.checkAll = !this.caheckAll;
                    if (this.checkAll) {
                        this.selectedRows = {{ json_encode($with_holdings->pluck('id')->toArray()) }}; 
                    } else {
                        this.selectedRows = []; 
                    }
                        console.log(this.selectedRows); // Debugging line
                    },

                    // Handle deletion
                    deleteRows() {
                        if (this.selectedRows.length === 0) {
                            alert('No rows selected for deletion.');
                            return;
                        }

                        if (confirm('Are you sure you want to archive the selected row/s?')) {
                            fetch('/tax_return/with_holding/1601EQ/destroy', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ ids: this.selectedRows })
                            })
                            .then(response => {
                                if (response.ok) {
                                    location.reload();
                                } else {
                                    alert('Error deleting rows.');
                                }
                            });
                        }
                    },

                    // Cancel selection
                    cancelSelection() {
                        this.selectedRows = []; 
                        this.checkAll = false;
                        this.showCheckboxes = false; 
                        this.showDeleteCancelButtons = false;
                    },

                    get selectedCount() {
                        return this.selectedRows.length;
                    }
                }" class="mb-12 mx-12 overflow-hidden max-w-full rounded-md border-neutral-300 dark:border-neutral-700">

                    <!-- Fourth Header -->
                    <div class="container mx-auto">
                        <div class="flex flex-row space-x-2 items-center justify-between">
                            <!-- Search row -->
                            <div class="relative w-80 p-4">
                                <form x-target="tableid" action="/1601EQ" role="search" aria-label="Table" autocomplete="off">
                                    <input 
                                        type="search" 
                                        name="search" 
                                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-900 focus:border-sky-900" 
                                        aria-label="Search Term" 
                                        placeholder="Search..." 
                                        @input.debounce="$el.form.requestSubmit()" 
                                        @search="$el.form.requestSubmit()"
                                    >
                                </form>
                                <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                            <!-- End row -->
                            <div class="mx-auto space-x-4 pr-6">
                                <button 
                                    type="button" 
                                    @click="showCheckboxes = !showCheckboxes; showDeleteCancelButtons = !showDeleteCancelButtons" 
                                    class="border px-3 py-2 rounded-lg text-sm hover:border-red-500 hover:text-red-500 transition"
                                >
                                    <i class="fa fa-trash"></i> Delete
                                </button>

                                <button 
                                    x-data 
                                    x-on:click="$dispatch('open-generate-modal')" 
                                    class="border px-3 py-2 rounded-lg text-sm hover:border-green-500 hover:text-green-500 transition"
                                >
                                    <i class="fa fa-plus-circle"></i> Generate
                                </button>

                                <button type="button">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="w-full px-6 py-4">
                        <div class="flex space-x-4 border-b border-gray-300">
                            <a href="{{ route('with_holding.1601C') }}" 
                            class="pb-2 text-sm {{ request()->routeIs('with_holding.1601C') ? 'text-blue-500 border-b-2 border-blue-500 font-semibold' : 'text-gray-500 hover:text-blue-500' }}">
                                1601C
                            </a>
                            <a href="{{ route('with_holding.0619E') }}" 
                            class="pb-2 text-sm {{ request()->routeIs('with_holding.0619E') ? 'text-blue-500 border-b-2 border-blue-500 font-semibold' : 'text-gray-500 hover:text-blue-500' }}">
                                0619E
                            </a>
                            <a href="{{ route('with_holding.1601EQ') }}" 
                            class="pb-2 text-sm {{ request()->routeIs('with_holding.1601EQ') ? 'text-blue-500 border-b-2 border-blue-500 font-semibold' : 'text-gray-500 hover:text-blue-500' }}">
                                1601EQ
                            </a>
                            <a href="{{ route('with_holding.1604C') }}" 
                            class="pb-2 text-sm {{ request()->routeIs('with_holding.1604C') ? 'text-blue-500 border-b-2 border-blue-500 font-semibold' : 'text-gray-500 hover:text-blue-500' }}">
                                1604C
                            </a>
                            <a href="{{ route('with_holding.1604E') }}" 
                            class="pb-2 text-sm {{ request()->routeIs('with_holding.1604E') ? 'text-blue-500 border-b-2 border-blue-500 font-semibold' : 'text-gray-500 hover:text-blue-500' }}">
                                1604E
                            </a>
                        </div>
                    </div>
                    <hr>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-neutral-600 border-collapse border-spacing-0">
                            <thead class="border-b bg-gray-100 text-sm font-semibold text-neutral-900">
                                <tr>
                                    <th scope="col" class="p-4">
                                        <label for="checkAll" x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                            <div class="relative flex items-center">
                                                <input type="checkbox" x-model="checkAll" id="checkAll" @click="toggleAll()" class="peer relative w-5 h-5 appearance-none border border-gray-400 bg-white checked:bg-blue-900 rounded-full checked:border-blue-900 checked:before:content-['✓'] checked:before:text-white checked:before:text-center focus:outline-none transition"
                                                />
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="2" class="pointer-events-none invisible absolute left-1/2 top-1/2 w-3.5 h-3.5 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                </svg>
                                            </div>  
                                        </label>
                                    </th>
                                    <th scope="col" class="py-3 px-4 text-left">Title</th>
                                    <th scope="col" class="py-3 px-4 text-left">Period</th>
                                    <th scope="col" class="py-3 px-4 text-left">Created By</th>
                                    <th scope="col" class="py-3 px-4 text-left">Status</th>
                                    <th scope="col" class="py-3 px-4 text-left">Date Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($with_holdings as $with_holding)
                                    <tr class="hover:bg-blue-50 cursor-pointer ease-in-out">
                                        <td class="p-4">
                                            <label x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                                <div class="relative flex items-center">
                                                    <input type="checkbox" @click="toggleCheckbox('{{ $with_holding->id }}')" :checked="selectedRows.includes('{{ $with_holding->id }}')" id="with_holding{{ $with_holding->id }}" 
                                                        class="peer relative w-5 h-5 appearance-none border border-gray-400 bg-white checked:bg-blue-900 rounded-full checked:border-blue-900 checked:before:content-['✓'] checked:before:text-white checked:before:text-center focus:outline-none transition"
                                                    />
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="2" class="pointer-events-none invisible absolute left-1/2 top-1/2 w-3.5 h-3.5 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible dark:text-black">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                    </svg>
                                                </div>
                                            </label>
                                        </td>
                                        <td class="py-3 px-4" onclick="window.location='{{ route('with_holding.1601EQ_Qap', ['id' => $with_holding->id]) }}'">{{ $with_holding->title ?? 'N/A' }}</td>
                                        <td class="py-3 px-4">      
                                            Quarter {{ $with_holding->quarter }} 
                                            ({{ $with_holding->quarter === 1 ? 'January - March' : 
                                                ($with_holding->quarter === 2 ? 'April - June' : 
                                                ($with_holding->quarter === 3 ? 'July - September' : 'October - December')) }} 
                                            {{ $with_holding->year ?? 'N/A' }})
                                        </td>
                                        <td>{{ $with_holding->creator->name ?? 'N/A' }}</td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-50">
                                                {{ $with_holding->status ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">{{ $with_holding->created_at->format('F d, Y g:i A') ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-3 px-4 text-center text-gray-500">
                                            No withholding records found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $with_holdings->links('vendor.pagination.custom') }}
                        </div>
                    </div>

                    <!-- Action Buttons --> 
                    <div x-show="showDeleteCancelButtons" class="flex justify-center py-4" x-cloak>
                        <button @click="deleteRows" class="border px-3 py-2 mx-2 rounded-lg text-sm text-red-600 hover:bg-red-100 transition">
                            <i class="fa fa-trash"></i> Delete Selected <span x-text="selectedCount > 0 ? '(' + selectedCount + ')' : ''"></span>
                        </button>
                        <button @click="cancelSelection" class="border px-3 py-2 mx-2 rounded-lg text-sm text-neutral-600 hover:bg-neutral-100 transition">
                            <i class="fa fa-times"></i> Cancel
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div x-data="{ open: false, month: '', year: '' }" @open-generate-modal.window="open = true" x-cloak>
        <!-- Modal Background -->
        <div 
            x-show="open" 
            class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center transition-opacity"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <!-- Modal Content -->
            <div 
                class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full relative"
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="scale-95 opacity-0"
                x-transition:enter-end="scale-100 opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="scale-100 opacity-100"
                x-transition:leave-end="scale-95 opacity-0"
            >
                <h2 class="text-lg font-semibold mb-4 text-center">
                    Monthly Remittance Form of Creditable Income Taxes Withheld (Expanded)
                </h2>

                <form method="POST" action="{{ route('with_holding.1601EQ.generate') }}">
                    @csrf

                    <input type="hidden" name="type" value="1601EQ">

                    <!-- Year Selection -->
                    <div class="mb-4">
                        <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                        <select 
                            id="year" 
                            name="year" 
                            x-model="year"
                            class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            required
                        >
                            <option value="" disabled>Select Year</option>
                            @php
                                $currentYear = date('Y');
                                $startYear = $currentYear - 100;
                            @endphp
                            @for ($year = $startYear; $year <= $currentYear; $year++)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Quarterly Selection -->
                    <div class="mb-4">
                        <label for="quarter" class="block text-sm font-medium text-gray-700">Quarter</label>
                        <select 
                            id="quarter" 
                            name="quarter" 
                            x-model="quarter"
                            class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            required
                        >
                            <option value="" disabled>Select Quarter</option>
                            <option value="1">Q1 (January - March)</option>
                            <option value="2">Q2 (April - June)</option>
                            <option value="3">Q3 (July - September)</option>
                            <option value="4">Q4 (October - December)</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end mt-6">
                        <button 
                            type="button" 
                            @click="open = false" 
                            class="mr-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg transition"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition"
                        >
                            Generate
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <!-- Script -->
    <script>
        document.addEventListener('search', event => {
            window.location.href = `?search=${event.detail.search}`;
        });

        document.addEventListener('filter', event => {
            const url = new URL(window.location.href);
            url.searchParams.set('type', event.detail.type);
            window.location.href = url.toString();
        });

        function toggleCheckboxes() {
            // Get all checkbox elements inside the table body
            const rowCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]');

            // Clear or populate the selectedRows array based on checkAll state
            if (this.checkAll) {
                // Check all checkboxes and add their IDs to selectedRows
                this.selectedRows = Array.from(rowCheckboxes).map(checkbox => checkbox.dataset.id);
            } else {
                // Uncheck all checkboxes and clear selectedRows
                this.selectedRows = [];
            }

            // Update the D OM to reflect the state
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checkAll;
            });
        }
    </script>
</x-app-layout>
