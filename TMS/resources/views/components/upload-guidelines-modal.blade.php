<div 
        x-data="{ show: false }"
        x-show="show"
        x-on:open-upload-modal.window="show = true"
        x-on:close-upload-modal.window="show = false"
        x-effect="document.body.classList.toggle('overflow-hidden', show)"
        class="fixed z-50 inset-0 flex items-center justify-center m-2 px-6"
        x-cloak
    >
    <!-- Modal background -->
    <div class="fixed inset-0 bg-gray-300 opacity-60"></div>

    <!-- Modal container -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-auto h-auto z-10 overflow-hidden">
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

        <!-- Modal body -->
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