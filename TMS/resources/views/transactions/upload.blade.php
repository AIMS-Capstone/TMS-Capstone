<x-app-layout>
    
    <div class="py-12">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{-- Page Header Content --}}
                <div class="container mx-auto my-auto pt-6">
                    <div class="px-10">
                        
                        <div class="flex flex-col items-start space-y-2">
                            <div class="flex items-center">
                                <a href="{{ route('transactions') }}">
                                    <button class="text-zinc-600 hover:text-zinc-700 flex items-center space-x-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" viewBox="0 0 24 24">
                                            <g fill="none" stroke="#52525b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M16 12H8m4-4l-4 4l4 4"/></g>
                                        </svg>
                                        <span class="text-zinc-600 text-sm font-normal hover:text-zinc-700">Go back</span>
                                    </button>
                                </a>
                            </div>
                            <p class="font-bold text-3xl auth-color">Upload Image</p>
                            
                        </div>
                        
                    </div>
                    
                </div>

                <hr class="mx-8 my-2">
                

                <div class="container mx-auto my-auto pt-2 pb-10 px-10">
                    <div class="flex gap-4 bg-slate-100 rounded-lg">
                        {{-- Left: Upload --}}
                     

                        <div class="w-full md:w-1/2 flex flex-col bg-white items-center border rounded-lg">
                            <div class="pt-12 pb-4 px-auto">
                                <form action="{{ route('transactions.upload') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <label for="upload-file" class="flex flex-col items-center justify-center w-full h-[535px] border-2 border-blue-900 border-dashed rounded-t-lg cursor-pointer bg-zinc-50 hover:bg-zinc-200">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6 {{ session('uploaded_image') || old('uploaded_image') ? 'hidden' : '' }}" id="upload-placeholder">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mb-4 text-blue-900" viewBox="0 0 16 16">
                                                <g fill="currentColor" fill-rule="evenodd">
                                                    <path d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773C16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318C1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593c.143-.863.698-1.723 1.464-2.383"/>
                                                    <path d="M7.646 4.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V14.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708z"/>
                                                </g>
                                            </svg>
                                            <p class="mb-2 text-sm text-blue-900"><span>Drag & Drop an image</span> here or <b class="underline">Browse</b></p>
                                            <p class="text-xs text-blue-900">Supported formats: JPEG and PNG</p>
                                        </div>
                                        <!-- Image Preview -->
                                        <img id="image-preview" 
                                        class="h-full object-cover rounded-t-lg {{ session('uploaded_image') || old('uploaded_image') ? '' : 'hidden' }}" 
                                        src="{{ session('uploaded_image') ?? old('uploaded_image') }}" />
                                        
                                        <input id="upload-file" type="file" 
                                                                name="receipt" 
                                                                class="hidden" 
                                                                accept=".jpg, .jpeg, .png" 
                                                                required 
                                                                onchange="validateFile(this)" />
                                    </label>
                                    
                                    <p class="mt-4 text-xs text-zinc-500">Ensure your image is clear and readable for best results.
                                        {{-- commented dahil nagreresize siya --}}
                                        {{-- <x-upload-guidelines-modal /> --}}
                                        <button x-data x-on:click="$dispatch('open-upload-modal')" class="text-xs underline text-blue-600 hover:text-blue-900">
                                            See upload guidelines
                                        </button>
                                    </p>
                                    <div id="file-error" class="mt-2 text-red-600 text-sm hidden"></div>
                                    <div class="flex mt-6 justify-center gap-4">
                                        <button class="mr-2 font-semibold text-zinc-600 px-3 py-1 rounded-md hover:text-zinc-900 transition">Cancel</button>
                                        <button type="submit" class="px-6 py-2 inline-flex items-center font-semibold text-sm bg-blue-900 text-white rounded-lg hover:bg-blue-950">Scan Document</button>
                                    </div>
                                </form>
                            </div>
                            {{-- guidelines modal: nagreresize ang layout kapag naka uncomment yung x-modal sa taas --}}
                                <div 
                                        x-data="{ show: false }"
                                        x-show="show"
                                        x-on:open-upload-modal.window="show = true"
                                        x-on:close-upload-modal.window="show = false"
                                        x-effect="document.body.classList.toggle('overflow-hidden', show)"
                                        class="fixed z-50 inset-0 w-full flex items-center justify-center m-2 px-6"
                                        x-cloak
                                    >
                                    <!-- Modal background -->
                                    <div class="fixed inset-0 bg-zinc-300 opacity-60"></div>

                                    <!-- Modal container -->
                                    <div class="bg-white rounded-lg shadow-lg max-w-2xl mx-auto h-auto z-10 overflow-hidden">
                                        <!-- Modal header -->
                                        <div class="relative bg-blue-900 rounded-t-lg p-3 border-b border-opacity-80 flex items-center justify-center">
                                            <h1 class="text-lg font-bold text-white">Upload Guidelines</h1>
                                            <button 
                                                x-on:click="$dispatch('close-upload-modal')" 
                                                class="text-white absolute right-4 top-1/2 transform -translate-y-1/2"
                                            >
                                                &times;
                                            </button>
                                        </div>

                                        <!-- Modal body [the content is also at the file: upload-guidlelines-modal]-->
                                        <div class="p-10 text-zinc-700 text-xs leading-relaxed">
                                            <p>To ensure your transaction is processed smoothly, please follow these guidelines when uploading an image:</p>
                                            <ol class="list-decimal ml-6">
                                                <li><strong>Supported File Formats</strong>
                                                    <ul class="list-disc ml-4">
                                                        <li>Acceptable file types: <strong>JPEG (.jpg)</strong> and <strong>PNG (.png)</strong> only.</li>
                                                        <li>Make sure your image is in one of these formats before uploading.</li>
                                                    </ul>
                                                </li>
                                                <li><strong>File Size</strong>
                                                    <ul class="list-disc ml-4">
                                                        <li>Maximum file size: <strong>5 MB</strong>.</li>
                                                        <li>If your image exceeds this limit, please compress it before uploading.</li>
                                                    </ul>
                                                </li>
                                                <li><strong>Image Quality</strong>
                                                    <ul class="list-disc ml-4">
                                                        <li>For best results, upload <strong>high-resolution images</strong>.</li>
                                                        <li>Ensure the image is clear, with no blurriness or low-quality resolution.</li>
                                                    </ul>
                                                </li>
                                                <li><strong>Clear Text Visibility</strong>
                                                    <ul class="list-disc ml-4">
                                                        <li>Ensure that all text is <strong>clearly legible</strong>, without shadows, glare, or reflections.</li>
                                                        <li>Take the photo in good lighting to avoid dark or overexposed areas.</li>
                                                    </ul>
                                                </li>
                                                <li><strong>Document Alignment</strong>
                                                    <ul class="list-disc ml-4">
                                                        <li>The document should be <strong>properly aligned</strong> and centered in the image.</li>
                                                        <li>Make sure the entire document is visible, with no parts cut off.</li>
                                                    </ul>
                                                </li>
                                                <li><strong>One Image per Scan</strong>
                                                    <ul class="list-disc ml-4">
                                                        <li>You can upload only <strong>one image per scan</strong>.</li>
                                                        <li>Please do not attempt to upload multiple documents in a single scan.</li>
                                                    </ul>
                                                </li>
                                                <li><strong>Handwritten Text</strong>
                                                    <ul class="list-disc ml-4">
                                                        <li>Handwritten text may not be recognized correctly. You may need to <strong>manually edit</strong> or add missing details after uploading.</li>
                                                    </ul>
                                                </li>
                                                <li><strong>Review After Upload</strong>
                                                    <ul class="list-disc ml-4">
                                                        <li>After uploading, carefully review the <strong>extracted information</strong>. Correct any errors before saving the transaction.</li>
                                                    </ul>
                                                </li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                        </div> 
                        
                        {{-- Right: Extracted Text from Receipt --}}
                        <div class="w-full md:w-1/2 bg-slate-100 mt-4 rounded-lg">
                            <div class="pt-5 pb-5 px-10">
                                <h1 class="text-2xl font-bold text-blue-900">Transaction Details</h1>
                                <p class="text-xs text-zinc-600 mb-4">Below are the details extracted from the scanned image. Please review the</br>extracted information, 
                                    manually correct any errors, or fill in any missing details.</br>Once done, proceed to save the transaction.
                                </p>
                             
                             
                                <form action="{{ route('transactions.storeUpload') }}" method="POST">
                                    @csrf

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-zinc-700">Vendor</label>
                                            <input type="text" name="vendor" 
                                                class="peer py-3 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-zinc-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none @error('vendor') border-b-red-500 @enderror" 
                                                value="{{ old('vendor', session('cleanedData')['vendor'] ?? null) }}" />
                                            @error('vendor')
                                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    
                                        <div>
                                            <label class="block text-sm font-semibold text-zinc-700">Customer TIN</label>
                                            <input type="text" name="customer_tin" 
                                                class="peer py-3 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-zinc-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none @error('customer_tin') border-b-red-500 @enderror" 
                                                value="{{ old('customer_tin', session('cleanedData')['tin'] ?? null) }}" />
                                            @error('customer_tin')
                                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    
                                        <div>
                                            <label class="block text-sm font-semibold text-zinc-700">Date</label>
                                            <input type="date" name="date" 
                                                class="peer py-3 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-zinc-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none @error('date') border-b-red-500 @enderror" 
                                                value="{{ old('date', session('cleanedData')['date'] ?? null ? \Carbon\Carbon::parse(session('cleanedData')['date'])->format('Y-m-d') : '') }}" />
                                            @error('date')
                                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    
                                        <div>
                                            <label class="block text-sm font-semibold text-zinc-700">Reference Number</label>
                                            <input type="text" name="reference_number" 
                                                class="peer py-3 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-zinc-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none @error('reference_number') border-b-red-500 @enderror" 
                                                value="{{ old('reference_number', session('cleanedData')['invoice_number'] ?? null) }}" />
                                            @error('reference_number')
                                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    
                                        <div>
                                            <label class="block text-sm font-semibold text-zinc-700">Organization Type</label>
                                            <select name="organization_type" 
                                                class="peer py-3 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-zinc-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none @error('organization_type') border-b-red-500 @enderror">
                                                <option value="Individual" {{ old('organization_type') == 'Individual' ? 'selected' : '' }}>Individual</option>
                                                <option value="Non-Individual" {{ old('organization_type') == 'Non-Individual' ? 'selected' : '' }}>Non-Individual</option>
                                            </select>
                                            @error('organization_type')
                                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    
                                        <div>
                                            <label class="block text-sm font-semibold text-zinc-700">City</label>
                                            <input type="text" name="city" 
                                                class="peer py-3 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-zinc-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none @error('city') border-b-red-500 @enderror" 
                                                value="{{ old('city') }}" />
                                            @error('city')
                                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    
                                        <div>
                                            <label class="block text-sm font-semibold text-zinc-700">Zip Code</label>
                                            <input type="text" name="zip_code" 
                                                class="peer py-3 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-zinc-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none @error('zip_code') border-b-red-500 @enderror" 
                                                value="{{ old('zip_code') }}" />
                                            @error('zip_code')
                                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    
                                        <div>
                                            <label class="block text-sm font-semibold text-zinc-700">Address</label>
                                            <input type="text" name="address" 
                                                class="peer py-3 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-zinc-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none @error('address') border-b-red-500 @enderror" 
                                                value="{{ old('address', session('cleanedData')['address'] ?? null) }}" />
                                            @error('address')
                                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    
                                        <div>
                                            <label class="block text-sm font-semibold text-zinc-700">Description</label>
                                            <input type="text" name="description" 
                                                class="peer py-3 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-zinc-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none @error('description') border-b-red-500 @enderror" 
                                                value="{{ old('description') }}" />
                                            @error('description')
                                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    
                                        <div>
                                            <label class="block text-sm font-semibold text-zinc-700">Tax Type</label>
                                            <select name="tax_type" 
                                                class="block py-2.5 px-0 w-full text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-zinc-200 appearance-none cursor-pointer focus:outline-none focus:ring-0 focus:border-blue-900 peer @error('tax_type') border-b-red-500 @enderror">
                                                @foreach($tax_types as $tax_type)
                                                    <option value="{{ $tax_type->id }}" {{ old('tax_type') == $tax_type->id ? 'selected' : '' }}>
                                                        {{ $tax_type->short_code }} - {{ $tax_type->tax_type }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('tax_type')
                                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    
                                        <div>
                                            <label class="block text-sm font-semibold text-zinc-700">ATC</label>
                                            <select name="tax_code" 
                                                class="block py-2.5 px-0 w-full text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-zinc-200 appearance-none cursor-pointer focus:outline-none focus:ring-0 focus:border-blue-900 peer @error('tax_code') border-b-red-500 @enderror">
                                                @foreach($tax_codes as $tax_code)
                                                    <option value="{{ $tax_code->id }}" {{ old('tax_code') == $tax_code->id ? 'selected' : '' }}>
                                                        {{ $tax_code->tax_code }} - {{ $tax_code->category }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('tax_code')
                                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    
                                        <div>
                                            <label class="block text-sm font-semibold text-zinc-700">COA</label>
                                            <select name="coa" 
                                                class="block py-2.5 px-0 w-full text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-zinc-200 appearance-none cursor-pointer focus:outline-none focus:ring-0 focus:border-blue-900 peer @error('coa') border-b-red-500 @enderror">
                                                @foreach($coas as $coa)
                                                    <option value="{{ $coa->id }}" {{ old('coa') == $coa->id ? 'selected' : '' }}>
                                                        {{ $coa->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('coa')
                                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    
                                        <div>
                                            <label class="block text-sm font-semibold text-zinc-700">Amount (VAT Inclusive)</label>
                                            <input type="text" name="amount" 
                                                class="peer py-3 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-zinc-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none @error('amount') border-b-red-500 @enderror" 
                                                value="{{ old('amount', session('cleanedData')['total_amount'] ?? null) }}" />
                                            @error('amount')
                                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    
                                        <div>
                                            <label class="block text-sm font-semibold text-zinc-700">Tax Amount</label>
                                            <input type="text" name="tax_amount" 
                                                class="peer py-3 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-zinc-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none @error('tax_amount') border-b-red-500 @enderror" 
                                                value="{{ old('tax_amount', session('cleanedData')['tax_amount'] ?? null) }}" />
                                            @error('tax_amount')
                                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    
                                        <div>
                                            <label class="block text-sm font-semibold text-zinc-700">Net Amount</label>
                                            <input type="text" name="net_amount" 
                                                class="peer py-3 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-zinc-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none @error('net_amount') border-b-red-500 @enderror" 
                                                value="{{ old('net_amount', session('cleanedData')['net_amount'] ?? null) }}" />
                                            @error('net_amount')
                                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                <div class="my-8 flex justify-center">
                                    
                                    <button type="submit" class="px-6 py-2 inline-flex items-center font-semibold text-sm bg-blue-900 text-white rounded-lg hover:bg-blue-950">Save Transaction</button>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>

    <script>
        function validateFile(input) {
            const file = input.files[0];
            const imagePreview = document.getElementById('image-preview');
            const placeholder = document.getElementById('upload-placeholder');
            const errorDiv = document.getElementById('file-error');
            
            // Reset error message
            errorDiv.textContent = '';
            errorDiv.classList.add('hidden');

            if (file) {
                const fileSizeMB = file.size / 1024 / 1024;
                const fileType = file.type;
                let isValid = true;
                let errorMessage = [];

                // Validate file size
                if (fileSizeMB > 5) {
                    errorMessage.push('File size must be less than 5 MB');
                    isValid = false;
                }

                // Validate file type
                if (!['image/jpeg', 'image/png'].includes(fileType)) {
                    errorMessage.push('Only JPEG and PNG files are allowed');
                    isValid = false;
                }

                if (!isValid) {
                    errorDiv.textContent = errorMessage.join('. ');
                    errorDiv.classList.remove('hidden');
                    input.value = '';
                    imagePreview.classList.add('hidden');
                    placeholder.classList.remove('hidden');
                    return false;
                }

                // Show image preview if file is valid
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }

            return true;
        }

        function resetForm() {
            const form = document.getElementById('uploadForm');
            const imagePreview = document.getElementById('image-preview');
            const placeholder = document.getElementById('upload-placeholder');
            const errorDiv = document.getElementById('file-error');
            
            form.reset();
            imagePreview.src = '';
            imagePreview.classList.add('hidden');
            placeholder.classList.remove('hidden');
            errorDiv.textContent = '';
            errorDiv.classList.add('hidden');
        }

        // Handle drag and drop
        const dropZone = document.querySelector('label[for="upload-file"]');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('bg-zinc-200');
        }

        function unhighlight(e) {
            dropZone.classList.remove('bg-zinc-200');
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            const fileInput = document.getElementById('upload-file');
            
            fileInput.files = files;
            validateFile(fileInput);
        }
    </script>
</x-app-layout>