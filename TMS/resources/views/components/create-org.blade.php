<x-organization-layout>
    <div class="bg-white px-20">
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex container my-auto pt-6 items-center justify-between relative">
                    <div class="absolute -left-16 flex items-center">
                        <a href="{{ route('org-setup') }}">
                            <button class="text-zinc-600 hover:text-zinc-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" viewBox="0 0 24 24"><g fill="none" stroke="#52525b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M16 12H8m4-4l-4 4l4 4"/></g></svg>
                                <span class="text-zinc-600 text-sm font-normal hover:text-zinc-700">Go back to Organizations</span>
                            </button>
                        </a>
                    </div>
                    <div class="w-full text-center">
                        <h1 class="text-3xl font-bold text-blue-900">Create Organization</h1>
                    </div>
                </div>
            </div>
        </div>
        {{-- TAB STEPPER --}}
        <div class="flex justify-center border-b border-gray-200 mb-4 px-4 sm:px-6 lg:px-8">
            <div id="tabs" class="flex space-x-12 sm:space-x-14 lg:space-x-16" aria-label="Tabs" role="tablist" aria-orientation="horizontal">
              <button class="tab text-blue-900 font-semibold py-2 px-4 border-b-4 border-blue-900"
                id="tab-class"
                role="tab"
                aria-selected="true">Classification</button>
              <button class="tab text-gray-500 py-2 px-4 focus:outline-none"
                id="tab-bg"
                role="tab"
                aria-selected="true">Background</button>
              <button class="tab text-gray-500 py-2 px-4 focus:outline-none"
                id="tab-add"
                role="tab"
                aria-selected="true">Address</button>
              <button class="tab text-gray-500 py-2 px-4 focus:outline-none"
                id="tab-contact"
                role="tab"
                aria-selected="true">Contact</button>
              <button class="tab text-gray-500 py-2 px-4 focus:outline-none"
                id="tab-tax"
                role="tab"
                aria-selected="true">Tax Information</button>
              <button class="tab text-gray-500 py-2 px-4 focus:outline-none"
                id="tab-financial"
                role="tab"
                aria-selected="true">Financial Settings</button>
            </div>
        </div>

        <!-- Tab Content -->
        <form action="{{ route ('OrgSetup.store')}}" method="POST" x-data="{ showModal: false }" @submit.prevent="submitForm">
            @csrf 
            <div id="tab-content" class="container border border-gray-200 rounded-lg p-4 my-10 text-center max-w-full h-[500px] mx-auto flex flex-col">
                <!-- Classification Content -->
                <div class="tab-content-item classification-content">
                    <p class="p-10 text-zinc-600 font-medium text-lg">What is your organization classification?</p>
                    <div class="flex justify-center space-x-8">
                        <!-- Non-Individual Option -->
                        <label for="non_individual" class="flex flex-col items-center">
                            <input type="radio" name="type" id="non_individual" wire:model="type" value="{{ __('Non-Individual') }}" class="hidden peer">
                            <div class="group flex items-center justify-center w-44 h-44 rounded-full bg-gray-100 border-2 border-transparent peer-checked:bg-blue-900 peer-checked:border-blue-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20" viewBox="0 0 36 36">
                                    <path d="M31 8h-9v25h11V10a2 2 0 0 0-2-2m-5 17h-2v-2h2Zm0-5h-2v-2h2Zm0-5h-2v-2h2Zm4 10h-2v-2h2Zm0-5h-2v-2h2Zm0-5h-2v-2h2Z" class="group-peer-checked:fill-yellow-500 fill-gray-400"/>
                                    <path d="M17.88 3H6.12A2.12 2.12 0 0 0 4 5.12V33h5v-3h6v3h5V5.12A2.12 2.12 0 0 0 17.88 3M9 25H7v-2h2Zm0-5H7v-2h2Zm0-5H7v-2h2Zm0-5H7V8h2Zm4 15h-2v-2h2Zm0-5h-2v-2h2Zm0-5h-2v-2h2Zm0-5h-2V8h2Zm4 15h-2v-2h2Zm0-5h-2v-2h2Zm0-5h-2v-2h2Zm0-5h-2V8h2Z" class="group-peer-checked:fill-yellow-500 fill-gray-400"/>
                                </svg>
                            </div>
                            <span class="text-blue-900 font-semibold mt-2">Non-Individual</span>
                        </label>
                        <!-- Individual Option -->
                        <label for="individual" class="flex flex-col items-center">
                            <input type="radio" name="type" id="individual" wire:model="type" value="{{ __('Individual') }}" class="hidden peer">
                            <div class="group flex items-center justify-center w-44 h-44 rounded-full bg-gray-100 border-2 border-transparent peer-checked:bg-blue-900 peer-checked:border-blue-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20" viewBox="0 0 16 16">
                                    <path d="M8 8a3 3 0 1 0 0-6a3 3 0 0 0 0 6m4.735 6c.618 0 1.093-.561.872-1.139a6.002 6.002 0 0 0-11.215 0c-.22.578.254 1.139.872 1.139z" class="group-peer-checked:fill-yellow-500 fill-gray-400"/>
                                </svg>
                            </div>
                            <span class="text-blue-900 font-semibold mt-2">Individual</span>
                        </label>
                    </div>
                </div>
            
                <!-- Background Content -->
                <div class="tab-content-item">
                    <p class="p-10 text-zinc-600 font-medium text-lg">What should we call this organization?</p>
                    <div class="flex flex-col items-center h-full">
                        <div class="flex flex-col mb-4 w-80">
                            <div class="flex flex-col">
                                <x-field-label for="registration_name" value="{{ __('Registered Name') }}" class="mb-2 text-left" />
                                <x-input type="text" name="registration_name" id="registration_name" wire:model="registration_name" placeholder="e.g. ABC Corp" />
                            </div>
                        </div>
                        <div class="flex flex-col w-80">
                            <div class="flex flex-col">
                                <x-field-label for="line_of_business" value="{{ __('Line of Business') }}" class="mb-2 text-left" />
                                <x-input type="text" name="line_of_business" id="line_of_business" wire:model="line_of_business" placeholder="Line of Business" maxlength="50"/>
                            </div>
                        </div>
                    </div>
                </div>
            
                <!-- Address Content -->
                <div class="tab-content-item">
                    <p class="p-10 text-zinc-600 font-medium text-lg">Organization Address Information</p>
                    <div class="flex flex-col items-center h-full">
                        <div class="flex flex-col mb-4 w-full max-w-md">
                            <div class="flex flex-col">
                                <x-field-label for="address_line" value="{{ __('Address Line') }}" class="mb-2 text-left" />
                                <x-input type="text" name="address_line" id="address_line" wire:model="address_line" placeholder="e.g. ESI Bldg 124 Yakal Street" />
                            </div>
                        </div>

                        <div class="flex flex-col mb-4 w-full max-w-md">
                            <div class="flex flex-col">
                                <x-field-label for="region" value="{{ __('Region') }}" class="mb-2 text-left" />
                                <select wire:model="region" name="region" id="region" class="cursor-pointer border rounded-xl px-4 py-2 w-full text-sm border-gray-300 placeholder:text-gray-400 placeholder:font-light placeholder:text-sm focus:border-slate-500 focus:ring-slate-500 shadow-sm">
                                    <option value="" disabled selected>Select Region</option>
                                    @foreach($regions as $region)
                                        <option value="{{ $region['designation'] }}">{{ $region['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-col mb-4 w-full max-w-md">
                            <div class="flex flex-col">
                                <x-field-label for="province" value="{{ __('Province') }}" class="mb-2 text-left" />
                                <select wire:model="province" name="province" id="province" class="cursor-pointer border rounded-xl px-4 py-2 w-full text-sm border-gray-300 placeholder:text-gray-400 placeholder:font-light placeholder:text-sm focus:border-slate-500 focus:ring-slate-500 shadow-sm" {{ !$region ? 'disabled' : '' }}>
                                    <option value="" disabled selected>Select Province</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-row space-x-4 w-full max-w-md">
                            <div class="flex flex-col w-full">
                                <x-field-label for="city" value="{{ __('City') }}" class="mb-2 text-left" />
                                <select wire:model="city" name="city" id="city" class="cursor-pointer border rounded-xl px-4 py-2 w-full text-sm border-gray-300 placeholder:text-gray-400 placeholder:font-light placeholder:text-sm focus:border-slate-500 focus:ring-slate-500 shadow-sm">
                                    <option value="" disabled selected>Select City</option>
                                </select>
                            </div>
                            <div class="flex flex-col w-32">
                                <x-field-label for="zip_code" value="{{ __('Zip Code') }}" class="mb-2 text-left readonly" />
                                <x-input type="text" name="zip_code" id="zip_code" wire:model="zip_code" placeholder="1203" class="border rounded-xl px-4 py-2 w-full readonly" maxlength="4"/>
                            </div>
                        </div>
                    </div>
                </div>
           
                <!-- Contact Content -->
                <div class="tab-content-item">
                    <p class="p-10 text-zinc-600 font-medium text-lg">Contact Information</p>
                    <div class="flex flex-col items-center h-full">
                        <div class="flex flex-col mb-4 w-80">
                            <div class="flex flex-col">
                                <x-field-label for="contact_number" value="{{ __('Contact Number') }}" class="mb-2 text-left" />
                                <x-input type="text" name="contact_number" id="contact_number" 
                                wire:model="contact_number" 
                                placeholder="e.g. 09123456789" 
                                maxlength="11"
                                pattern="^09\d{9}$" 
                                class="border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-900 focus:border-blue-900 block w-full p-2.5"/>
                            </div>
                        </div>
                        <div class="flex flex-col w-80">
                            <div class="flex flex-col">
                                <x-field-label for="email" value="{{ __('Email Address') }}" class="mb-2 text-left" />
                                <x-input type="email" name="email" id="email" x-model="email" placeholder="Enter Email Address" onblur="validateEmail()"/>
                            </div>
                            <small id="validationMessage" class="text-red-600 text-sm"></small>
                        </div>
                    </div>
                </div>
            
                <!-- Tax Information Content -->
                <div class="tab-content-item">
                    <div class="flex justify-between">
                        {{-- TIN & RDO --}}
                        <div class="flex flex-col w-1/2 pr-4">
                            <p class="p-10 text-zinc-600 font-medium text-lg">Its TIN and RDO?</p>
                            <div class="flex flex-col mb-4 items-center">
                                <div class="flex flex-col w-80">
                                    <x-field-label for="tin" value="{{ __('Tax Identification Number (TIN)') }}" class="mb-2 text-left" />
                                    <x-input type="text" name="tin" id="tin" wire:model="tin" placeholder="e.g. 000-000-000-00000" class="w-80" maxlength="20"/>
                                </div>
                            </div>
                        
                            <div class="flex flex-col items-center">
                                <div class="flex flex-col 80">
                                    <x-field-label for="rdo" value="{{ __('Revenue District Office (RDO)') }}" class="mb-2 text-left" />
                                    <select wire:model="selectedRDO" name="rdo" id="rdo" class="select2 border appearance-none rounded-xl px-4 py-2 w-80 text-sm truncate border-gray-300 placeholder:text-gray-400 placeholder:font-light placeholder:text-sm focus:border-slate-500 focus:ring-slate-500 shadow-sm cursor-pointer">
                                        <option value="" disabled selected>Select RDO</option>
                                        @foreach($rdos as $rdo)
                                            <option value="{{ $rdo->id }}">{{ $rdo->rdo_code }} - {{ $rdo->location }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                
                        {{-- Tax Type --}}
                        <div class="flex flex-col w-1/2 pl-4">
                            <p class="p-10 text-zinc-600 font-medium text-lg mb-4">Tax type organization complies with?</p>
                            <div class="flex flex-col gap-2 justify-center items-center">
                                <label for="percentage_tax" class="flex w-80 min-w-[14rem] cursor-pointer items-center justify-start gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 font-medium text-slate-700">
                                    <input type="radio" name="tax_type" id="percentage_tax" wire:model="tax_type" value="{{ __('Percentage Tax') }}" class="relative h-4 w-4 appearance-none rounded-full border border-gray-200 bg-white checked:border-blue-900 checked:bg-blue-900 focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-slate-800">
                                    <span class="text-sm">Percentage Tax</span>
                                </label>
                                <label for="value_added_tax" class="flex w-80 min-w-[14rem] cursor-pointer items-center justify-start gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 font-medium text-slate-700">
                                    <input type="radio" name="tax_type" id="value_added_tax" wire:model="tax_type" value="{{ __('Value-Added Tax') }}" class="relative h-4 w-4 appearance-none rounded-full border border-gray-200 bg-white checked:border-blue-900 checked:bg-blue-900 focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-slate-800">
                                    <span class="text-sm">Value-Added Tax</span>
                                </label>
                                <label for="tax_exempt" class="flex w-80 min-w-[14rem] cursor-pointer items-center justify-start gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 font-medium text-slate-700">
                                    <input type="radio" name="tax_type" id="tax_exempt" wire:model="tax_type" value="{{ __('Tax Exempt') }}" class="relative h-4 w-4 appearance-none rounded-full border border-gray-200 bg-white checked:border-blue-900 checked:bg-blue-900 focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-slate-800">
                                    <span class="text-sm">Tax Exempt</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            
                <!-- Financial Settings Content -->
                <div class="tab-content-item">
                    <p class="p-10 text-zinc-600 font-medium text-lg">Lastly, enter the organization's<br/>Start Date and Financial Year End</p>
                    <div class="flex flex-col items-center h-full">
                        <div class="flex flex-col mb-2 w-80">
                            <div class="flex flex-col">
                                <x-field-label for="start_date" value="{{ __('Start Date') }}" class="mb-2 text-left" />
                                <input name="start_date" id="start_date" wire:model="start_date" type="date"
                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" 
                                max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" 
                                class="border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-900 focus:border-blue-900 block w-full" placeholder="Enter Start Date">
                            </div>
                        </div>

                        <div class="flex flex-col mb-2 w-80">
                            <div class="flex flex-col">
                                <x-field-label for="registration_date" value="{{ __('Registration Date') }}" class="mb-2 text-left" />
                                <input name="registration_date" id="registration_date" wire:model="registration_date" type="date" max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-900 focus:border-blue-900 block w-full" 
                                placeholder="Enter Registration Date">
                            </div>
                        </div>

                        <div class="flex flex-col mb-2 w-80">
                            <div class="flex flex-col">
                                <x-field-label for="financial_year_end" value="{{ __('Financial Year End') }}" class="mb-2 text-left" />
                                <select name="financial_year_end" id="financial_year_end" wire:model="financial_year_end" class="cursor-pointer border rounded-xl px-4 py-2 w-full mb-4 text-sm border-gray-300 placeholder:text-gray-400 placeholder:font-light placeholder:text-sm focus:border-slate-500 focus:ring-slate-500 shadow-sm">
                                    <option value="" disabled selected>Select Financial Year End</option>
                                    <option value="Calendar Year">Calendar Year</option>
                                    <option value="Fiscal Year">Fiscal Year</option>
                                </select>
                            </div>
                        </div>
                        
                    </div>
                </div>
            
                <!-- Back and Next Buttons -->
                <div class="inset-x-20 bottom-auto flex justify-between mt-auto px-4">
                    <button id="prevBtn" class="border border-blue-900 bg-white text-blue-900 font-bold px-4 py-2 rounded-xl disabled:opacity-50">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5" viewBox="0 0 16 16">
                                <path fill="#1e3a8a" fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m10.25.75a.75.75 0 0 0 0-1.5H6.56l1.22-1.22a.75.75 0 0 0-1.06-1.06l-2.5 2.5a.75.75 0 0 0 0 1.06l2.5 2.5a.75.75 0 1 0 1.06-1.06L6.56 8.75z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                        Previous
                    </button>
                    <button id="nextBtn" disabled class="bg-blue-900 text-white font-semibold px-4 py-2 w-28 rounded-xl disabled:opacity-50">
                        Next
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5" viewBox="0 0 24 24">
                                <g fill="none" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="M8 12h8m-4 4l4-4l-4-4"/>
                                </g>
                            </svg>
                        </span>
                    </button>
                </div>

                {{-- <!-- Success Modal -->
                <div x-data="{ showModal: {{ session()->has('success') ? 'true' : 'false' }} }" x-show="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-30" x-cloak>
                    <div class="bg-white rounded-lg shadow-lg p-6 text-center max-w-lg w-full">
                        <!-- Centered Image -->
                        <div class="flex justify-center mb-4">
                            <img src="{{ asset('images/Ok-amico.png') }}" alt="Organization Added" class="w-40 h-40 mr-6">
                        </div>
                        <h2 class="text-emerald-500 font-bold text-3xl whitespace-normal mb-4">Organization Added</h2>
                        <p class="font-normal text-sm mb-4">The organization has been successfully<br>added! Go back to the Organizxations to open<br/> and start the session.</p>
                        <div class="flex items-center justify-center mt-4 mb-4">
                            <button type="button" onclick="window.location.href='{{ route('org-setup') }}'" @click="showModal = false" class="inline-flex items-center w-48 justify-center py-2 bg-emerald-500 border border-transparent rounded-xl 
                            font-bold text-sm text-white tracking-widest hover:bg-emerald-600 focus:bg-emerald-700 active:bg-emerald-700 focus:outline-none disabled:opacity-50 transition ease-in-out duration-150">
                                {{ __('Go Back to Organizations') }} 
                                <div class="ms-2 w-5 h-5 flex items-center justify-center border-2 border-white rounded-full">
                                    <svg class="rtl:rotate-180 w-3.5 h-3.5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                    </svg>
                                </div>
                            </button>
                        </div>
                    </div>
                </div> --}}

                

            </div>
    
                    <!-- Success Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50" x-cloak>
        <div class="bg-white rounded-lg shadow-lg p-6 w-80 text-center">
            <div class="mb-4">
                <svg class="w-12 h-12 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2l4-4m0 6a9 9 0 1 0-6 0Z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold">Organization Added</h3>
            <p class="text-sm text-gray-600 mt-2">The organization has been successfully added! Go back to the setup page to continue.</p>
            <button @click="window.location.href='{{ url('/org-setup') }}'" 
                    class="mt-4 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                Go to Setup
            </button>
        </div>
    </div>
        </form> 
    </div>

<script>    
  
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content-item');
    let prevBtn = document.getElementById('prevBtn');
    let nextBtn = document.getElementById('nextBtn');
    const saveBtnHtml = `
        <button type="submit" id="saveBtn" class="bg-blue-900 text-white font-semibold px-4 py-2 rounded-xl">
            Save
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5" viewBox="0 0 24 24">
                    <g fill="none" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M8 12h8m-4 4l4-4l-4-4"/>
                    </g>
                </svg>
            </span>
        </button>
    `;
    let currentTab = 0;

    function updateButtonStyles(button) {
        if (button.disabled) {
            button.classList.remove('bg-blue-900', 'text-white');
            button.classList.add('bg-gray-400', 'text-gray-700', 'cursor-not-allowed');
        } else {
            button.classList.remove('bg-gray-400', 'text-gray-700', 'cursor-not-allowed');
            button.classList.add('bg-blue-900', 'text-white');
        }
    }

    function updateButtonsVisibility() {
        const activeTabId = tabs[currentTab].id;
        if (activeTabId === 'tab-financial') {
            nextBtn.outerHTML = saveBtnHtml;
            nextBtn = document.getElementById('saveBtn');
            nextBtn.addEventListener('click', handleSubmit); // Add form submission handling
        } else {
            if (!document.getElementById('nextBtn')) {
                document.querySelector('.flex.justify-between').insertAdjacentHTML('beforeend', `
                    <button id="nextBtn" class="bg-blue-900 text-white font-semibold px-4 py-2 rounded-xl">
                        Next
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5" viewBox="0 0 24 24">
                                <g fill="none" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="M8 12h8m-4 4l4-4l-4-4"/>
                                </g>
                            </svg>
                        </span>
                    </button>
                `);
                nextBtn = document.getElementById('nextBtn');
                nextBtn.addEventListener('click', function(event) {
                    event.preventDefault();
                    if (currentTab < tabs.length - 1) {
                        currentTab++;
                        updateTabs();
                    }
                });
            }
        }
        checkTabFields(); 
    }

    function activateTab(tabId) {
        tabContents.forEach(item => item.classList.add('hidden'));
        const tabIndex = Array.from(tabs).findIndex(tab => tab.id === tabId);
        if (tabIndex !== -1) {
            tabContents[tabIndex].classList.remove('hidden');
        }
        updateButtonsVisibility();
    }

    function updateTabs() {
        tabs.forEach((tab, index) => {
            tab.classList.toggle('text-blue-900', index === currentTab);
            tab.classList.toggle('font-semibold', index === currentTab);
            tab.classList.toggle('border-b-4', index === currentTab);
            tab.classList.toggle('border-blue-900', index === currentTab);
            tab.classList.toggle('rounded-t-lg', index === currentTab);
            tab.classList.toggle('text-gray-500', index !== currentTab);
            tabContents[index].classList.toggle('hidden', index !== currentTab);
        });
        prevBtn.disabled = currentTab === 0;
        nextBtn.disabled = currentTab === tabs.length - 1;
        updateButtonsVisibility();
    }

    function checkTabFields() {
        const activeTabContent = tabContents[currentTab];
        const inputs = activeTabContent.querySelectorAll('input');
        const selects = activeTabContent.querySelectorAll('select');
        let allFilled = Array.from(inputs).every(input => input.value.trim() !== '') &&
                        Array.from(selects).every(select => select.value !== '');

        nextBtn.disabled = !allFilled;
        if (document.getElementById('saveBtn')) {
            document.getElementById('saveBtn').disabled = !allFilled;
        }
    }

    function handleSubmit(event) {
        const saveBtn = document.getElementById('saveBtn');
        if (saveBtn && saveBtn.disabled) {
            event.preventDefault(); // Prevent submission if Save button is disabled
        }
    }

    prevBtn.addEventListener('click', (event) => {
        event.preventDefault();
        if (currentTab > 0) {
            currentTab--;
            updateTabs();
        }
    });

    nextBtn.addEventListener('click', (event) => {
        event.preventDefault();
        if (currentTab < tabs.length - 1) {
            currentTab++;
            updateTabs();
        }
    });

    tabs.forEach((tab, index) => {
        tab.addEventListener('click', () => {
            currentTab = index;
            updateTabs();
        });
    });

    tabContents.forEach(tabContent => {
        tabContent.querySelectorAll('input, select').forEach(element => {
            element.addEventListener('input', checkTabFields);
            element.addEventListener('change', checkTabFields);
        });
    });

    updateTabs(); // Initial setup
});

    // Contact Number
    document.addEventListener('DOMContentLoaded', function () {
        const contactNumberInput = document.getElementById('contact_number');

        contactNumberInput.addEventListener('input', function () {
            // Allow typing digits only, and remove any non-numeric characters
            this.value = this.value.replace(/[^\d]/g, '');
            // Allow typing only up to 11 digits
            if (this.value.length > 11) {
                this.value = this.value.slice(0, 11);
            }
            // Ensure the number starts with "09"
            if (this.value.length >= 2 && !this.value.startsWith('09')) {
                this.value = ''; // Clear input if it doesn't start with "09"
            }
        });
    });

    // Email Address
    function validateEmail() {
        const email = document.getElementById("email");
        const validationMessage = document.getElementById("validationMessage");
        const emailInput = email.value.trim();
        // Check if email is empty
        if (emailInput === "") {
            validationMessage.innerText = "Please enter an email address";
            return false;
        }
        // Split email into local part and domain part
        const emailParts = emailInput.split("@");
        if (emailParts.length !== 2) {
            validationMessage.innerText = "Please enter a valid email address - missing or too many '@' symbols";
            return false;
        }
        const localPart = emailParts[0];
        const domainPart = emailParts[1];
        // Validate local part
        const localPartRegex = /^[a-zA-Z0-9!#$%&'*+\-/=?^_`{|}~]+(\.[a-zA-Z0-9!#$%&'*+\-/=?^_`{|}~]+)*$/;
        if (!localPartRegex.test(localPart)) {
            validationMessage.innerText = "Invalid characters";
            return false;
        }
        if (localPart.includes("..") || localPart.startsWith(".") || localPart.endsWith(".")) {
            validationMessage.innerText = "Invalid use of periods";
            return false;
        }
        // Validate domain part
        const domainPartRegex = /^[a-zA-Z0-9.-]+$/;
        if (!domainPartRegex.test(domainPart)) {
            validationMessage.innerText = "Invalid characters";
            return false;
        }
        if (domainPart.includes("--") || domainPart.startsWith("-") || domainPart.endsWith("-")) {
            validationMessage.innerText = "Invalid use of hyphens";
            return false;
        }
        // Validate top-level domain (TLD)
        const tldRegex = /^[a-zA-Z]{2,}$/;
        const domainParts = domainPart.split(".");
        if (domainParts.length < 2 || !tldRegex.test(domainParts[domainParts.length - 1])) {
            validationMessage.innerText = "Invalid email";
            return false;
        }
        // If all checks pass, email is valid
        // validationMessage.innerText = "Email is valid!";
        return true;
    }

    //Date
    // Start Date
    document.addEventListener('DOMContentLoaded', function () {
        const startDateInput = document.getElementById('start_date');
        
        // Ensure the selected date is exactly today
        startDateInput.addEventListener('change', function () {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Reset time to avoid comparison issues
        });
    });
    // Registration Date
    document.addEventListener('DOMContentLoaded', function () {
        const registrationDateInput = document.getElementById('registration_date');
        registrationDateInput.addEventListener('change', function () {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Reset time to compare only dates
        });
    });
        
   document.addEventListener('DOMContentLoaded', function() {
    const provinces = @json($provinces);
    const municipalities = @json($municipalities); // Pass municipalities with zip codes to JavaScript

    // Handle region change
    document.getElementById('region').addEventListener('change', function() {
        const selectedRegion = this.value;

        // Clear and disable province dropdown
        const provinceSelect = document.getElementById('province');
        provinceSelect.innerHTML = '<option value="" disabled selected>Select Province</option>';
        provinceSelect.disabled = true;

        // Load provinces based on selected region
        provinces.forEach(province => {
            if (province.region === selectedRegion) {
                const option = document.createElement('option');
                option.value = province.name;
                option.textContent = province.name;
                provinceSelect.appendChild(option);
            }
        });
        provinceSelect.disabled = false; // Enable the province dropdown
    });

    // Handle province change
    document.getElementById('province').addEventListener('change', function() {
        const selectedProvince = this.value;

        // Clear and disable city dropdown
        const citySelect = document.getElementById('city');
        citySelect.innerHTML = '<option value="" disabled selected>Select City</option>';
        citySelect.disabled = true;

        // Load municipalities based on selected province
        municipalities.forEach(municipality => {
            if (municipality.province === selectedProvince) {
                const option = document.createElement('option');
                option.value = municipality.name;
                option.textContent = municipality.name;
                citySelect.appendChild(option);
            }
        });
        citySelect.disabled = false; // Enable the city dropdown
    });

    // Handle city change and set ZIP code
    document.getElementById('city').addEventListener('change', function() {
        const selectedCity = this.value;

        // Find and set ZIP code based on selected city
        const zipCodeInput = document.getElementById('zip_code');
        const selectedMunicipality = municipalities.find(municipality => municipality.name === selectedCity);
        if (selectedMunicipality) {
            zipCodeInput.value = selectedMunicipality.zip_code || '';
        } else {
            zipCodeInput.value = '';
        }
    });
});

function submitForm() {
    const formData = new FormData(document.querySelector('form'));

    fetch('{{ route('OrgSetup.store') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => {
        if (response.ok) {
            this.showModal = true;
        } else {
            return response.json().then(data => {
                console.error('Error:', data);
                alert('Failed to save organization. Please check your input.');
            });
        }
    })
    .catch(error => console.error('Error:', error));
}

    </script>

</x-organization-layout>
    {{-- </body>
</html> --}}