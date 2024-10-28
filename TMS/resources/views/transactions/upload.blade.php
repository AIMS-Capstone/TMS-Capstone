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
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mb-4 text-blue-900" viewBox="0 0 16 16">
                                                <g fill="currentColor" fill-rule="evenodd">
                                                    <path d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773C16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318C1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593c.143-.863.698-1.723 1.464-2.383"/>
                                                    <path d="M7.646 4.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V14.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708z"/>
                                                </g>
                                            </svg>
                                            <p class="mb-2 text-sm text-blue-900"><span>Drag & Drop an image</span> here or <b class="underline">Browse</b></p>
                                            <p class="text-xs text-blue-900">Supported formats: JPEG and PNG</p>
                                        </div>
                                        <input id="upload-file" type="file" name="receipt" class="hidden" accept=".jpg, .jpeg, .png" required onchange="validateFile()" />
                                    </label>
                        
                                    <p class="mt-4 text-xs text-zinc-500">Ensure your image is clear and readable for best results.
                                        {{-- commented dahil nagreresize siya --}}
                                        {{-- <x-upload-guidelines-modal /> --}}
                                        <button x-data x-on:click="$dispatch('open-upload-modal')" class="text-xs underline text-blue-600 hover:text-blue-900">
                                            See upload guidelines
                                        </button>
                                    </p>
                        
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
                
                                @if(session('extractedText'))
                                <div>
                                    <h2>Extracted Text from Receipt:</h2>
                                    <pre>{{ session('extractedText') }}</pre>
                                </div>
                                @endif

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-zinc-700">Customer</label>
                                        <input type="text" class="peer py-3 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-zinc-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-zinc-700">Customer TIN</label>
                                        <input type="text" class="peer py-3 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-zinc-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-zinc-700">Date</label>
                                        <input type="date" class="peer py-3 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-zinc-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-zinc-700">Invoice Number</label>
                                        <input type="text" class="peer py-3 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-zinc-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-zinc-700">Reference</label>
                                        <input type="text" class="peer py-3 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-zinc-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none" />
                                    </div>
                                    <div>
                                        
                                        <label class="block text-sm font-semibold text-zinc-700">Description</label>
                                        <input type="text" class="peer py-3 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-zinc-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-zinc-700">Tax Type</label>
                                        <select class="block py-2.5 px-0 w-full text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-zinc-200 appearance-none cursor-pointer focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-zinc-700">ATC</label>
                                        <select class="block py-2.5 px-0 w-full text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-zinc-200 appearance-none cursor-pointer focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-zinc-700">COA</label>
                                        <select class="block py-2.5 px-0 w-full text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-zinc-200 appearance-none cursor-pointer focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-zinc-700">Amount (VAT Inclusive)</label>
                                        <input type="text" class="peer py-3 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-zinc-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-zinc-700">Tax Amount</label>
                                        <input type="text" class="peer py-3 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-zinc-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-zinc-700">Net Amount</label>
                                        <input type="text" class="peer py-3 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-zinc-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none" />
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
        function validateFile() {
            const fileInput = document.getElementById('upload-file');
            const file = fileInput.files[0];
            
            if (file) {
                const fileSizeMB = file.size / 1024 / 1024;
                const fileType = file.type;
    
                if (fileSizeMB > 5) {
                    alert('File size must be less than 5 MB.');
                    fileInput.value = '';
                    return false;
                }
    
                if (!['image/jpeg', 'image/png'].includes(fileType)) {
                    alert('Only JPEG and PNG files are allowed.');
                    fileInput.value = '';
                    return false;
                }
            }
    
            return true;
        }
    </script>
</x-app-layout>