<div 
    x-data="{ show: false }"
    x-show="show"
    x-on:open-import-modal.window="show = true"
    x-on:close-modal.window="show = false"
    x-effect="document.body.classList.toggle('overflow-hidden', show)"
    class="fixed z-50 inset-0 flex items-center justify-center m-2"
    x-cloak
>
    <!-- Modal background -->
    <div class="fixed inset-0 bg-gray-300 opacity-60"></div>

    <!-- Modal container -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-screen-xl max-h-screen mx-auto h-auto z-10 overflow-hidden">
        <!-- Modal header -->
        <div class="flex justify-center items-center p-3 bg-sky-900 border-opacity-80 w-full">
            <h1 class="text-lg font-bold text-white">Chart of Accounts</h1>
        </div>
                
        <!-- Modal body -->
        <div class="p-4 h-[475px] overflow-auto">

            <!-- Tab(Hindi ko pa alam paano yung may bilog tas bridge, hindi ko mapagana ng ayos) -->
            <div class="flex justify-around px-3 py-3 border-bottom">
                <div class="tab text-gray-500 cursor-pointer" id="tab-upload">Upload CSV</div>
                <div class="tab text-gray-500 cursor-pointer" id="tab-fields">Column Mapping</div>
                <div class="tab text-gray-500 cursor-pointer" id="tab-review">Review Import</div>
                <div class="tab text-gray-500 cursor-pointer" id="tab-complete">Complete Import</div>
            </div>

            <!-- Start ng form -->
            <form method="POST" action="{{ route('coa.store') }}" enctype="multipart/form-data" id="import-form">
                @csrf
                <input type="hidden" name="submit_action" value="import">

                <!-- Step 1: Upload CSV -->
                    <div class="tab-content-item" id="tab-content-upload">
                        <div class="flex flex-col p-6 space-y-4 justify-center items-center h-full">
                            <div class="p-12 w-full max-w-screen-lg border text-sm"> 
                                <!-- Header -->
                                <div class="text-center">
                                    <h1 class="text-base font-semibold text-sky-900">Importing</h1> 
                                    <h2 class="text-xl font-semibold text-sky-900">Chart of Accounts from a CSV File</h2> 
                                    <p class="mt-2 text-xs text-gray-500"> 
                                        By default all CSV files can be imported and can be mapped in required <br> fields later,
                                        but you can also use our CSV Template.
                                    </p>
                                </div>

                                <!-- Instructions -->
                                <div class="p-4 rounded-lg w-full max-w-screen-md overflow-y-auto">
                                    <h3 class="font-semibold text-gray-600 text-sm">Preparing your CSV Files</h3> <!-- Reduced font size -->
                                    <ul class="list-disc pl-5 text-gray-600 text-xs mt-2 space-y-1"> <!-- Reduced font size -->
                                        <li>A CSV file containing chart of accounts information (You can
                                            <a href="{{ url('coa/import_template')}}" class="text-sky-500 hover:underline">download</a> this template for importing Chart of Accounts).
                                        </li>
                                        <li>Make sure to enter a valid <strong>Account Type</strong>
                                            (<a href="{{ url('coa/account_type_template')}}" class="text-sky-500 hover:underline">download list</a>).
                                        </li>
                                        <li>Fill Sub Category if the Account Type is
                                            <strong>Ordinary Allowance Itemized Deductions, Assets, or Liabilities</strong>
                                            (<a href="#" class="text-sky-500 hover:underline">download list</a>).
                                        </li>
                                        <li>For <strong>Code</strong>, it must be unique.</li>
                                        <li><strong>Name</strong> is required.</li>
                                        <li><strong>Description</strong> is optional.</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- File upload section -->
                            <div class="w-full justify-start mt-4 ps-20">
                                <label class="block mb-2 text-sm text-gray-500"><b>Choose a file to import</b></label>
                                <div class="flex items-center space-x-4">
                                    <label class="cursor-pointer">
                                        <span class="px-4 py-2 bg-white border border-sky-900 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-900 hover:bg-gray-50">
                                            Browse
                                        </span>
                                        <input type="file" name="sample" id="file" class="hidden" required onchange="showFileName()">
                                    </label>
                                    <span id="file-name" class="text-sm text-gray-500">No file chosen</span>
                                </div>
                                <p class="mt-2 text-xs text-gray-400">The file you import must be a CSV (Comma Separated Values) file.</p>
                            </div>
                        </div>
                    </div>

                <!-- Step 2: Specify Fields -->
                    <div class="tab-content-item hidden" id="tab-content-fields">
                        <div class="flex flex-col p-6 space-y-4 justify-center items-center h-full">
                            <div class="p-12 w-full max-w-screen-lg border text-sm"> 
                                <h2 class="font-semibold text-lg justify-center text-center text-blue-800">Column Mapping</h2>
                                <p class="text-center text-gray-600 mb-6">Map the columns in your CSV file to the required transaction fields (<span class="text-red-500">*</span>).</p>
                                <div class="grid grid-cols-2 gap-y-4 gap-x-8">
                                    <!-- Account Type -->
                                    <div class="flex items-center">
                                        <label for="accountType" class="block font-semibold">Account Type <span class="text-red-500">*</span></label>
                                    </div>
                                    <div>
                                        <input type="text" id="accountType" class="block w-full p-2 border rounded-md" value="Account Type" required>
                                    </div>

                                    <!-- Sub Category -->
                                    <div class="flex items-center">
                                        <label for="subCategory" class="block font-semibold">Sub Category</label>
                                    </div>
                                    <div>
                                        <input type="text" id="subCategory" class="block w-full p-2 border rounded-md" value="Sub Category">
                                    </div>

                                    <!-- Code -->
                                    <div class="flex items-center">
                                        <label for="code" class="block font-semibold">Code <span class="text-red-500">*</span></label>
                                    </div>
                                    <div>
                                        <input type="text" id="code" class="block w-full p-2 border rounded-md" value="Code" required>
                                    </div>

                                    <!-- Name -->
                                    <div class="flex items-center">
                                        <label for="name" class="block font-semibold">Name <span class="text-red-500">*</span></label>
                                    </div>
                                    <div>
                                        <input type="text" id="name" class="block w-full p-2 border rounded-md" value="Name" required>
                                    </div>

                                    <!-- Description -->
                                    <div class="flex items-center">
                                        <label for="description" class="block font-semibold">Description</label>
                                    </div>
                                    <div>
                                        <input type="text" id="description" class="block w-full p-2 border rounded-md" value="Description">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

<!-- Step 3: Review Import -->
<div class="tab-content-item hidden" id="tab-content-review">
    <h2 class="font-semibold text-lg mb-4">Review Import</h2>
    <p class="mb-4">Review the imported data before finalizing the import.</p>
    
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-3 px-6 text-left text-sm font-semibold text-gray-700 border-b">Type</th>
                    <th class="py-3 px-6 text-left text-sm font-semibold text-gray-700 border-b">Code</th>
                    <th class="py-3 px-6 text-left text-sm font-semibold text-gray-700 border-b">Name</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($rows) && count($rows) > 0)
                    @foreach($rows as $row)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-6 border-b text-gray-800">{{ $row[0] }}</td> <!-- Type -->
                        <td class="py-3 px-6 border-b text-gray-800">{{ $row[2] }}</td> <!-- Code -->
                        <td class="py-3 px-6 border-b text-gray-800">{{ $row[3] }}</td> <!-- Name -->
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3" class="py-3 px-6 text-center text-gray-500">No data available</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>


                <!-- Step 4: Complete Import -->
                <div class="tab-content-item hidden" id="tab-content-complete">
                    <div class="flex flex-col p-6 space-y-4 justify-center items-center h-full">
                        <div class="p-12 h-full max-h-screen-md w-full max-w-screen-lg border border-green-400 bg-green-100 text-sm rounded-md flex flex-col justify-center items-center">
                            <!-- Icon Container -->
                            <div class="icon-container flex justify-center items-center">
                                <div class="icon-background flex justify-center items-center bg-green-700 rounded-full p-6 w-24 h-24">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-16 h-16 text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>
                            <h2 class="font-semibold text-xl text-green-700 mt-4">Import Successful</h2>
                            <p class="text-gray-700 text-lg">Your CSV with filename <strong>Chart of Accounts.csv</strong> was successfully imported.</p>
                        </div>
                    </div>
                </div>

                    <!-- Modal Footer --> 
                    <div id="footer" class="flex justify-between px-8">
                        <!-- Previous Button -->
                        <button id="prevBtn" type="button" class="px-4 py-2 bg-gray-500 text-white rounded-md hidden">Previous</button>

                        <div class="flex justify-end space-x-4">
                        <!-- Cancel Button -->
                        <button type="button" x-on:click="show = false" id="cancelBtn" class="px-4 py-2 bg-gray-500 text-white rounded-md">Cancel</button>
                        <!-- Navigation Buttons -->
                        <div class="flex space-x-4">
                            <button id="nextBtn" type="button" class="px-4 py-2 bg-sky-900 text-white rounded-md">Next</button>
                            <button type="submit" id="saveBtn" class="px-4 py-2 bg-sky-900 text-white rounded-md hidden">Done</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab');
            const tabContents = document.querySelectorAll('.tab-content-item');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const saveBtn = document.getElementById('saveBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const footer = document.getElementById('footer');
            const requiredField = document.querySelectorAll('#tab-content-fields input[required]');

            let currentTab = 0;

            function activateTab(index) {
                tabs.forEach((tab, idx) => {
                    tab.classList.toggle('text-blue-900', idx === index);
                    tab.classList.toggle('font-semibold', idx === index);
                    tabContents[idx].classList.toggle('hidden', idx !== index);
                });

                // Toggle Previous button visibility
                if (index === 0) {
                    prevBtn.classList.add('hidden'); // Hide Previous button on the first step
                    footer.classList.remove('justify-between');
                    footer.classList.add('justify-end'); // Align to end when Previous is hidden
                } else {
                    prevBtn.classList.remove('hidden');
                    footer.classList.remove('justify-end');
                    footer.classList.add('justify-between'); // Spread out buttons when Previous is visible
                }

                // On the last step, hide Next and Cancel, show Done
                if (index === tabs.length - 1) {
                    cancelBtn.classList.add('hidden'); // Hide cancel on last step
                    saveBtn.classList.remove('hidden'); // Show Done button
                    nextBtn.classList.add('hidden'); // Hide Next button
                    prevBtn.classList.add('hidden');
                    footer.classList.remove('justify-between'); // Remove justify-between
                    footer.classList.add('justify-end'); // Align Done button to the end
                } else {
                    cancelBtn.classList.remove('hidden');
                    saveBtn.classList.add('hidden');
                    nextBtn.classList.remove('hidden');
                }
                    // Recheck required fields for the current step
                    checkRequiredFields();
            }

            nextBtn.addEventListener('click', () => {
                if (currentTab < tabs.length - 1) {
                    currentTab++;
                    activateTab(currentTab);
                }
            });

            prevBtn.addEventListener('click', () => {
                if (currentTab > 0) {
                    currentTab--;
                    activateTab(currentTab);
                }
            });

            // Initialize the tab display
            activateTab(currentTab);

            // Disable the Next button initially
            nextBtn.disabled = true;
            nextBtn.classList.add('opacity-50', 'cursor-not-allowed');

            // Function to check if all required fields are filled
            function checkRequiredFields() {
                let allValid = true;
                // Get only the required fields from the current tab
                const requiredFields = tabContents[currentTab].querySelectorAll('input[required]');

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        allValid = false;
                    }
                });
                
                if (allValid) {
                    nextBtn.disabled = false;
                    nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    nextBtn.disabled = true;
                    nextBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }

            // Listen for input changes on all required fields across all tabs
            document.querySelectorAll('input[required]').forEach(field => {
                field.addEventListener('input', checkRequiredFields);
            });
        });

        function showFileName() {
            const fileInput = document.getElementById('file');
            const fileNameDisplay = document.getElementById('file-name');
            const nextBtn = document.getElementById('nextBtn'); // To disable the Next button if file is invalid
            const allowedExtensions = ['xlsx'];

            if (fileInput.files.length > 0) {
                const fileName = fileInput.files[0].name;
                const fileExtension = fileName.split('.').pop().toLowerCase();

                // Check if the file is an Excel file
                if (!allowedExtensions.includes(fileExtension)) {
                    fileNameDisplay.textContent = "Invalid file type! Please select an Excel (.xlsx) file.";
                    fileNameDisplay.classList.add('text-red-500');
                    nextBtn.disabled = true; // Disable the next button if invalid file type
                    nextBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    fileInput.value = ""; // Clear the file input
                } else {
                    fileNameDisplay.textContent = fileName;
                    fileNameDisplay.classList.remove('text-red-500');
                    nextBtn.disabled = false; // Enable next button
                    nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            } else {
                fileNameDisplay.textContent = "No file chosen";
                nextBtn.disabled = true; // Disable Next if no file is selected
                nextBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }

        // Modify the file input element to accept only Excel files
        document.getElementById('file').setAttribute('accept', '.xlsx');

</script>
