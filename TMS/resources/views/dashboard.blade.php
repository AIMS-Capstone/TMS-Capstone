<x-app-layout>
    <div class="py-12 h-dvh">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- <div class=" overflow-hidden shadow-xl sm:rounded-lg"> --}}
            <div class="grid grid-cols-4 gap-4 whitespace-nowrap text-wrap">
                <div class="container p-6 col-span-2 row-span-3 bg-white border-gray-200 rounded-lg">
                    <div class="flex items-center space-x-2 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 36 36"><path fill="#172554" d="M32.25 6h-4v3a2.2 2.2 0 1 1-4.4 0V6H12.2v3a2.2 2.2 0 0 1-4.4 0V6h-4A1.78 1.78 0 0 0 2 7.81v22.38A1.78 1.78 0 0 0 3.75 32h28.5A1.78 1.78 0 0 0 34 30.19V7.81A1.78 1.78 0 0 0 32.25 6M10 26H8v-2h2Zm0-5H8v-2h2Zm0-5H8v-2h2Zm6 10h-2v-2h2Zm0-5h-2v-2h2Zm0-5h-2v-2h2Zm6 10h-2v-2h2Zm0-5h-2v-2h2Zm0-5h-2v-2h2Zm6 10h-2v-2h2Zm0-5h-2v-2h2Zm0-5h-2v-2h2Z" class="clr-i-solid clr-i-solid-path-1"/><path fill="#172554" d="M10 10a1 1 0 0 0 1-1V3a1 1 0 0 0-2 0v6a1 1 0 0 0 1 1" class="clr-i-solid clr-i-solid-path-2"/><path fill="#172554" d="M26 10a1 1 0 0 0 1-1V3a1 1 0 0 0-2 0v6a1 1 0 0 0 1 1" class="clr-i-solid clr-i-solid-path-3"/><path fill="none" d="M0 0h36v36H0z"/></svg>
                        <span class="font-bold text-2xl text-blue-950 leading-tight">Tax Reminder</span>
                    </div>
                    <p class="font-normal text-sm">Stay updated with essential tax deadlines and obligations. Easily keep track of important
                        filling and payment dates to ensure seamless compliance with the BIR regulations.
                    </p>
                    <div class="my-3"><hr /></div>
                    <div class="tabs">
                        <div class="flex">
                            <ul class="flex bg-gray-100 rounded-full transition-all duration-300 overflow-hidden">
                                <li>
                                <a href="javascript:void(0)" class="inline-block py-3 px-6 text-blue-950 hover:text-blue-950 font-normal text-sm tab-active:bg-blue-950 tab-active:rounded-full tab-active:text-white active tablink whitespace-nowrap" data-tab="tabs-with-background-1" role="tab">Today</a>
                                </li>
                                <li>
                                <a href="javascript:void(0)" class="inline-block py-3 px-6 text-blue-950 hover:text-blue-950 font-normal text-sm tab-active:bg-blue-950 tab-active:rounded-full tab-active:text-white tablink whitespace-nowrap" data-tab="tabs-with-background-2" role="tab">Upcoming</a>
                                </li>
                                <li>
                                <a href="javascript:void(0)" class="inline-block py-3 px-6 text-blue-950 hover:text-blue-950 font-normal text-sm tab-active:bg-blue-950 tab-active:rounded-full tab-active:text-white tablink whitespace-nowrap" data-tab="tabs-with-background-3" role="tab">Completed</a>
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
                <div class="p-6 col-span-1 row-span-1 bg-white border-gray-200 flex items-center space-x-2 px-6 py-6 rounded-lg">
                    <p class="font-medium text-sm text-gray-700 leading-tight">Total Filed</p>
                    
                </div>
                <div class="p-6 col-span-1 row-span-1 bg-white border-gray-200 flex items-center space-x-2 rounded-lg">
                    <span class="font-medium text-sm text-gray-700 leading-tight">Total Pending Tax</span>
                </div>
                <div class="p-6 col-span-1 row-span-1 bg-white border-gray-200 flex items-center space-x-2 rounded-lg">
                    <span class="font-medium text-sm text-gray-700 leading-tight">Total Non-Individual Client</span>
                </div>
                <div class="p-6 col-span-1 row-span-1 bg-white border-gray-200 flex items-center space-x-2 rounded-lg">
                    <span class="font-medium text-sm text-gray-700 leading-tight">Total Individual Client</span>
                    <h3>8,910</h3>
                </div>
            </div>
                
                {{-- <x-welcome /> --}}
        </div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-white whitespace-nowrap overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="grid grid-cols-2 grid-row-3">
                        <div class="flex items-center space-x-2 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24">
                                <g fill="none">
                                    <path fill="#172554" d="M21 7c0 2.21-4.03 4-9 4S3 9.21 3 7s4.03-4 9-4s9 1.79 9 4"/>
                                    <path stroke="#172554" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 7c0 2.21-4.03 4-9 4S3 9.21 3 7m18 0c0-2.21-4.03-4-9-4S3 4.79 3 7m18 0v5M3 7v5m18 0c0 2.21-4.03 4-9 4s-9-1.79-9-4m18 0v5c0 2.21-4.03 4-9 4s-9-1.79-9-4v-5"/>
                                </g>
                            </svg>                        
                            <span class="font-bold text-2xl text-blue-950 leading-tight">Return Summary</span>
                        </div>
                        <div class="flex items-center justify-end mb-4">
                            <a href="anu-itu" class="flex items-center space-x-2 font-bold text-sm underline decoration-2 text-blue-950 leading-tight">
                                <span>View All Business</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="-5 -5 24 24">
                                    <path fill="#172554" d="m10.586 5.657l-3.95-3.95A1 1 0 0 1 8.05.293l5.657 5.657a.997.997 0 0 1 0 1.414L8.05 13.021a1 1 0 1 1-1.414-1.414l3.95-3.95H1a1 1 0 1 1 0-2z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <div class="bg-white border border-gray-300 rounded-tl-lg rounded-tr-lg grid grid-cols-6 gap-4">
                        <div class="col-span-2 bg-blue-50 p-4 rounded-tl-lg relative">
                            <button id="dropdownButton" class="flex items-center justify-between w-full bg-blue-50 text-blue-950 font-bold">
                                <span>Select a Business</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-950" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <select id="dropdownContent" class="text-gray-700 mt-2 bg-blue-50">
                                <option>
                                    <p class="font-bold px-4 py-2 text-blue-950">Dator Builders</p>
                                    <p class="font-bold px-4 py-2 text-blue-950">Organization</p>
                                    <p class="font-bold px-4 py-2 text-blue-950">001-112-223-334</p>
                                </option>
                                <option>
                                    <div class="font-bold px-4 py-2 text-blue-950">Jeremie Builders</div>
                                    <div class="text-sm px-4 py-1 text-blue-950">Organization</div>
                                    <div class="text-sm px-4 py-1 text-blue-950">001-112-223-334</div>
                                </option>
                            </select>
                        </div>
                    
                        <div class="col-span-3 flex items-center space-x-4">
                            <!-- Category -->
                            <div class="flex flex-col mx-6">
                                <label class="font-bold text-blue-950">Category</label>
                                <select class="mt-1 border border-gray-300 rounded-md text-blue-950">
                                    <option>All</option>
                                    <!-- Other options -->
                                </select>
                            </div>
                            <div class="h-8 border-l border-gray-200"></div>
                            <div class="flex flex-col mx-6">
                                <label class="font-bold text-blue-950">Month</label>
                                <select class="mt-1 border border-gray-300 rounded-md text-blue-950">
                                    <option>All</option>
                                    <!-- Other options -->
                                </select>
                            </div>
                            <div class="h-8 border-l border-gray-200"></div>
                            <div class="flex flex-col mx-6">
                                <label class="font-bold text-blue-950">Year</label>
                                <select class="mt-1 border border-gray-300 rounded-md text-blue-950">
                                    <option>All</option>
                                    <!-- Other options -->
                                </select>
                            </div>
                            <div class="h-8 border-l border-gray-200"></div>
                            <div class="flex flex-col mx-6">
                                <label class="font-bold text-blue-950">Status</label>
                                <select class="mt-1 border border-gray-300 rounded-md text-blue-950">
                                    <option>All</option>
                                    <!-- Other options -->
                                </select>
                            </div>
                    
                            <!-- Show Return Button -->
                            <div class="flex items-end justify-end mx-6">
                                <button class="ml-4 bg-white border border-gray-300 rounded-md px-4 py-2 text-blue-950">
                                    Show Return
                                </button>
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

</x-app-layout>
