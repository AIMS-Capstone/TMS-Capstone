
<div class="py-12 h-full">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- <div class=" overflow-hidden shadow-xl sm:rounded-lg"> --}}
        <div class="grid grid-cols-4 gap-4 whitespace-nowrap text-wrap">
            <div class="container p-6 col-span-2 row-span-3 bg-white border-gray-200 rounded-lg">
                <div class="flex items-center space-x-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 36 36"><path fill="#172554" d="M32.25 6h-4v3a2.2 2.2 0 1 1-4.4 0V6H12.2v3a2.2 2.2 0 0 1-4.4 0V6h-4A1.78 1.78 0 0 0 2 7.81v22.38A1.78 1.78 0 0 0 3.75 32h28.5A1.78 1.78 0 0 0 34 30.19V7.81A1.78 1.78 0 0 0 32.25 6M10 26H8v-2h2Zm0-5H8v-2h2Zm0-5H8v-2h2Zm6 10h-2v-2h2Zm0-5h-2v-2h2Zm0-5h-2v-2h2Zm6 10h-2v-2h2Zm0-5h-2v-2h2Zm0-5h-2v-2h2Zm6 10h-2v-2h2Zm0-5h-2v-2h2Zm0-5h-2v-2h2Z" class="clr-i-solid clr-i-solid-path-1"/><path fill="#172554" d="M10 10a1 1 0 0 0 1-1V3a1 1 0 0 0-2 0v6a1 1 0 0 0 1 1" class="clr-i-solid clr-i-solid-path-2"/><path fill="#172554" d="M26 10a1 1 0 0 0 1-1V3a1 1 0 0 0-2 0v6a1 1 0 0 0 1 1" class="clr-i-solid clr-i-solid-path-3"/><path fill="none" d="M0 0h36v36H0z"/></svg>
                    <span class="font-bold text-2xl taxuri-color leading-tight">Tax Reminder</span>
                </div>
                <p class="font-normal text-sm">Stay updated with essential tax deadlines and obligations. Easily keep track of important
                    filling and payment dates to ensure seamless compliance with the BIR regulations.
                </p>
                <div class="my-3"><hr /></div>
                <div class="tabs">
                    <div class="flex">
                        <ul class="flex bg-gray-100 rounded-full transition-all duration-300 overflow-hidden">
                            <li>
                            <a href="javascript:void(0)" class="inline-block py-3 px-6 taxuri-color hover:text-blue-950 font-normal text-sm tab-active:bg-blue-950 tab-active:rounded-full tab-active:text-white active tablink whitespace-nowrap" data-tab="tabs-with-background-1" role="tab">Today</a>
                            </li>
                            <li>
                            <a href="javascript:void(0)" class="inline-block py-3 px-6 taxuri-color hover:text-blue-950 font-normal text-sm tab-active:bg-blue-950 tab-active:rounded-full tab-active:text-white tablink whitespace-nowrap" data-tab="tabs-with-background-2" role="tab">Upcoming</a>
                            </li>
                            <li>
                            <a href="javascript:void(0)" class="inline-block py-3 px-6 taxuri-color hover:text-blue-950 font-normal text-sm tab-active:bg-blue-950 tab-active:rounded-full tab-active:text-white tablink whitespace-nowrap" data-tab="tabs-with-background-3" role="tab">Completed</a>
                            </li>
                        </ul>
                    </div>
                    <div class="mt-3">
                        <div id="tabs-with-background-1" role="tabpanel" aria-labelledby="tabs-with-background-item-1" class="tabcontent">
                            <p class="text-gray-500 "> This is the <em class="font-semibold text-gray-800 ">first</em> item's tab body. </p>
                        </div>
                        <div id="tabs-with-background-2" class="hidden tabcontent" role="tabpanel" aria-labelledby="tabs-with-background-item-2">
                            <p class="text-gray-500 "> This is the <em class="font-semibold text-gray-800 ">second</em> item's tab body. </p>
                        </div>
                        <div id="tabs-with-background-3" class="hidden tabcontent" role="tabpanel" aria-labelledby="tabs-with-background-item-3">
                            <p class="text-gray-500 "> This is the <em class="font-semibold text-gray-800 ">third</em> item's tab body. </p>
                        </div>
                    </div>
                </div>
        
            </div>
        </div>                
    </div>
</div>

<script>
    document.getElementById('dropdownButton').addEventListener('click', function() {
        const content = document.getElementById('dropdownContent');
        content.classList.toggle('hidden');
    });

    document.querySelectorAll('#dropdownContent .cursor-pointer').forEach(item => {
        item.addEventListener('click', function() {
            const selectedText = this.getAttribute('data-value');
            document.getElementById('selectedBusiness').textContent = selectedText || 'Select a Business';
            document.getElementById('dropdownContent').classList.add('hidden');
        });
    });
</script>