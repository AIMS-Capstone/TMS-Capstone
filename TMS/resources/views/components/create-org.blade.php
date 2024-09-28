{{-- Previous look nasa "sample.blade.php" --}}
<x-organization-layout>
    <div class="bg-white px-20">
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex container my-auto pt-6 items-center justify-between relative">
                    <div class="absolute -left-16 flex items-center">
                        <a href="{{ route('org-setup') }}">
                            <button class="text-zinc-600 hover:text-zinc-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" viewBox="0 0 24 24"><g fill="none" stroke="#52525b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M16 12H8m4-4l-4 4l4 4"/></g></svg>
                                <span class="text-zinc-600 text-sm font-normal hover:text-zinc-700">Go back to portal</span>
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
        {{-- naka-comment form dahil nagiging 404 error or 419 page expired --}}
        <form action="{{ route ('OrgSetup.store')}}" method="POST">
            @csrf 
            <div id="tab-content" class="container border border-gray-200 rounded-lg p-4 my-10 text-center max-w-full h-[500px] mx-auto flex flex-col">
                <!-- Classification Content -->
                <div class="tab-content-item classification-content">
                    <p class="p-10 text-zinc-600 font-medium text-lg">What is your organization classification?</p>
                    <div class="flex justify-center space-x-8">
                        <!-- Non-Individual Option -->
                        <label for="non_individual" class="flex flex-col items-center">
                            <input type="radio" name="type" id="non_individual" wire:model="type" value="non-individual" class="hidden peer">
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
                            <input type="radio" name="type" id="individual" wire:model="type" value="individual" class="hidden peer">
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
                                <x-input type="text" name="line_of_business" id="line_of_business" wire:model="line_of_business" placeholder="Line of Business" />
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
                                <select wire:model="region" name="region" id="region" class="border rounded-xl px-4 py-2 w-full text-sm border-gray-300 placeholder:text-gray-400 placeholder:font-light placeholder:text-sm focus:border-slate-500 focus:ring-slate-500 shadow-sm">
                                    <option value="" disabled selected>Select Region</option>
                                    <!-- Add na lang siguro ng parang plugin na laravel (may need na migration ata)-->
                                    <option value="Region I – Ilocos Region">Region I – Ilocos Region</option>
                                    <option value="Region II – Cagayan Valley">Region II – Cagayan Valley</option>
                                    <option value="Region III – Central Luzon">Region III – Central Luzon</option>
                                    <option value="Region IV‑A – CALABARZON">Region IV‑A – CALABARZON</option>
                                    <option value="Region IV‑B – MIMAROPA">Region IV‑B – MIMAROPA</option>
                                    <option value="Region V – Bicol Region">Region V – Bicol Region</option>
                                    <option value="Region VI – Western Visayas">Region VI – Western Visayas</option>
                                    <option value="Region VII – Central Visayas">Region VII – Central Visayas</option>
                                    <option value="Region VIII – Eastern Visayas">Region VIII – Eastern Visayas</option>
                                    <option value="Region IX – Zamboanga Peninsula">Region IX – Zamboanga Peninsula</option>
                                    <option value="Region X – Northern Mindanao">Region X – Northern Mindanao</option>
                                    <option value="Region XI – Davao Region">Region XI – Davao Region</option>
                                    <option value="Region XII – SOCCSKSARGEN">Region XII – SOCCSKSARGEN</option>
                                    <option value="Region XIII – Caraga">Region XIII – Caraga</option>
                                    <option value="NCR – National Capital Region">NCR – National Capital Region</option>
                                    <option value="CAR – Cordillera Administrative Region">CAR – Cordillera Administrative Region</option>
                                    <option value="BARMM – Bangsamoro Autonomous Region in Muslim Mindanao">BARMM – Bangsamoro Autonomous Region in Muslim Mindanao</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-col mb-4 w-full max-w-md">
                            <div class="flex flex-col">
                                {{-- this will be dependent on the selected region --}}
                                <x-field-label for="province" value="{{ __('Province') }}" class="mb-2 text-left" />
                                <select wire:model="province" name="province" id="province" class="border rounded-xl px-4 py-2 w-full text-sm border-gray-300 placeholder:text-gray-400 placeholder:font-light placeholder:text-sm focus:border-slate-500 focus:ring-slate-500 shadow-sm">
                                    <option value="" disabled selected>Select Province</option>
                                    <!-- Add na lang siguro ng parang plugin na laravel (may need na migration ata)-->
                                    <option value="Cavite">Cavite</option>
                                    <option value="Laguna">Laguna</option>
                                    <option value="Batangas">Batangas</option>
                                    <option value="Rizal">Rizal</option>
                                    <option value="Quezon">Quezon</option>
                                </select>
                            </div>
                        </div>
                
                        <div class="flex flex-row space-x-4 w-full max-w-md">
                            <div class="flex flex-col w-full">
                                <x-field-label for="city" value="{{ __('City') }}" class="mb-2 text-left" />
                                <select wire:model="city" name="city" id="city" class="border rounded-xl px-4 py-2 w-full text-sm border-gray-300 placeholder:text-gray-400 placeholder:font-light placeholder:text-sm focus:border-slate-500 focus:ring-slate-500 shadow-sm">
                                    <option value="" disabled selected>Select City</option>
                                    <option value="Santa Rosa">Santa Rosa</option>
                                    <!-- Add na lang siguro ng parang plugin na laravel (may need na migration ata)-->
                                    <!-- Need siya na naka based don sa province and/or region na maseselect-->
                                </select>
                            </div>
                            <div class="flex flex-col w-32">
                                {{-- if possible, maging autofill siya based sa city na maseselect --}}
                                <x-field-label for="zip_code" value="{{ __('Zip Code') }}" class="mb-2 text-left" />
                                <x-input type="text" name="zip_code" id="zip_code" wire:model="zip_code" placeholder="1203" class="border rounded-xl px-4 py-2 w-full" />
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
                                <x-input type="text" name="contact_number" id="contact_number" wire:model="contact_number" placeholder="e.g. 09123456789" />
                            </div>
                        </div>
                        <div class="flex flex-col w-80">
                            <div class="flex flex-col">
                                <x-field-label for="email" value="{{ __('Email Address') }}" class="mb-2 text-left" />
                                <x-input type="email" name="email" id="email" x-model="email" placeholder="Enter Email Address" />
                            </div>
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
                                    <x-input type="text" name="tin" id="tin" wire:model="tin" placeholder="e.g. 000-000-000-000" class="w-80" />
                                </div>
                            </div>
                        
                            <div class="flex flex-col items-center">
                                <div class="flex flex-col 80">
                                    <x-field-label for="rdo" value="{{ __('Revenue District Office (RDO)') }}" class="mb-2 text-left" />
                                    <select wire:model="rdo" name="rdo" id="rdo" class="border rounded-xl px-4 py-2 w-80 text-sm truncate border-gray-300 placeholder:text-gray-400 placeholder:font-light placeholder:text-sm focus:border-slate-500 focus:ring-slate-500 shadow-sm cursor-pointer">
                                        <option value="" disabled selected>Select Revenue District Office (RDO)</option>
                                        <option value="RDO Code - 001 (Laoag City, Ilocos Norte)">RDO Code - 001 (Laoag City, Ilocos Norte)</option>
                                        <option value="RDO Code - 002 (Vigan, Ilocos Sur)">RDO Code - 002 (Vigan, Ilocos Sur)</option>
                                        <option value="RDO Code - 003 (San Fernando, La Union)">RDO Code - 003 (San Fernando, La Union)</option>
                                        <option value="RDO Code - 004 (Calasiao, West Pangasinan)">RDO Code - 004 (Calasiao, West Pangasinan)</option>
                                        <option value="RDO Code - 005 (Alaminos, Pangasinan)">RDO Code - 005 (Alaminos, Pangasinan)</option>
                                        <option value="RDO Code - 006 (Urdaneta, Pangasinan)">RDO Code - 006 (Urdaneta, Pangasinan)</option>
                                        <option value="RDO Code - 007 (Bangued, Abra)">RDO Code - 007 (Bangued, Abra)</option>
                                        <option value="RDO Code - 008 (Baguio City)">RDO Code - 008 (Baguio City)</option>
                                        <option value="RDO Code - 009 (La Trinidad, Benguet)">RDO Code - 009 (La Trinidad, Benguet)</option>
                                        <option value="RDO Code - 010 (Bontoc, Mt. Province)">RDO Code - 010 (Bontoc, Mt. Province)</option>
                                        <option value="RDO Code - 011 (Tabuk City, Kalinga)">RDO Code - 011 (Tabuk City, Kalinga)</option>
                                        <option value="RDO Code - 012 (Lagawe, Ifugao)">RDO Code - 012 (Lagawe, Ifugao)</option>
                                        <option value="RDO Code - 013 (Tuguegarao, Cagayan)">RDO Code - 013 (Tuguegarao, Cagayan)</option>
                                        <option value="RDO Code - 014 (Bayombong, Nueva Vizcaya)">RDO Code - 014 (Bayombong, Nueva Vizcaya)</option>
                                        <option value="RDO Code - 015 (Naguilian, Isabela)">RDO Code - 015 (Naguilian, Isabela)</option>
                                        <option value="RDO Code - 016 (Cabarroguis, Quirino)">RDO Code - 016 (Cabarroguis, Quirino)</option>
                                        <option value="RDO Code - 017A (Tarlac City, Tarlac)">RDO Code - 017A (Tarlac City, Tarlac)</option>
                                        <option value="RDO Code - 017B (Paniqui, Tarlac)">RDO Code - 017B (Paniqui, Tarlac)</option>
                                        <option value="RDO Code - 018 (Olongapo City)">RDO Code - 018 (Olongapo City)</option>
                                        <option value="RDO Code - 019 (Subic Bay Freeport Zone)">RDO Code - 019 (Subic Bay Freeport Zone)</option>
                                        <option value="RDO Code - 020 (Balanga, Bataan)">RDO Code - 020 (Balanga, Bataan)</option>
                                        <option value="RDO Code - 21A (North Pampanga)">RDO Code - 21A (North Pampanga)</option>
                                        <option value="RDO Code - 21B (South Pampanga)">RDO Code - 21B (South Pampanga)</option>
                                        <option value="RDO Code - 21C (Clark Freeport Zone)">RDO Code - 21C (Clark Freeport Zone)</option>
                                        <option value="RDO Code - 022 (Baler, Aurora)">RDO Code - 022 (Baler, Aurora)</option>
                                        <option value="RDO Code - 23A (North Nueva Ecija)">RDO Code - 23A (North Nueva Ecija)</option>
                                        <option value="RDO Code - 23B (South Nueva Ecija)">RDO Code - 23B (South Nueva Ecija)</option>
                                        <option value="RDO Code - 024 (Valenzuela City)">RDO Code - 024 (Valenzuela City)</option>
                                        <option value="RDO Code - 25A (Plaridel, Bulacan)">RDO Code - 25A (Plaridel, Bulacan)</option>
                                        <option value="RDO Code - 25B (Sta. Maria, Bulacan)">RDO Code - 25B (Sta. Maria, Bulacan)</option>
                                        <option value="RDO Code - 026 (Malabon-Navotas)">RDO Code - 026 (Malabon-Navotas)</option>
                                        <option value="RDO Code - 027 (Caloocan City)">RDO Code - 027 (Caloocan City)</option>
                                        <option value="RDO Code - 028 (Novaliches)">RDO Code - 028 (Novaliches)</option>
                                        <option value="RDO Code - 029 (Tondo – San Nicolas)">RDO Code - 029 (Tondo – San Nicolas)</option>
                                        <option value="RDO Code - 030 (Binondo)">RDO Code - 030 (Binondo)</option>
                                        <option value="RDO Code - 031 (Sta. Cruz)">RDO Code - 031 (Sta. Cruz)</option>
                                        <option value="RDO Code - 032 (Quiapo-Sampaloc-San Miguel-Sta. Mesa)">RDO Code - 032 (Quiapo-Sampaloc-San Miguel-Sta. Mesa)</option>
                                        <option value="RDO Code - 033 (Intramuros-Ermita-Malate)">RDO Code - 033 (Intramuros-Ermita-Malate)</option>
                                        <option value="RDO Code - 034 (Paco-Pandacan-Sta. Ana-San Andres)">RDO Code - 034 (Paco-Pandacan-Sta. Ana-San Andres)</option>
                                        <option value="RDO Code - 035 (Romblon)">RDO Code - 035 (Romblon)</option>
                                        <option value="RDO Code - 036 (Puerto Princesa)">RDO Code - 036 (Puerto Princesa)</option>
                                        <option value="RDO Code - 037 (San Jose, Occidental Mindoro)">RDO Code - 037 (San Jose, Occidental Mindoro)</option>
                                        <option value="RDO Code - 038 (North Quezon City)">RDO Code - 038 (North Quezon City)</option>
                                        <option value="RDO Code - 039 (South Quezon City)">RDO Code - 039 (South Quezon City)</option>
                                        <option value="RDO Code - 040 (Cubao)">RDO Code - 040 (Cubao)</option>
                                        <option value="RDO Code - 041 (Mandaluyong City)">RDO Code - 041 (Mandaluyong City)</option>
                                        <option value="RDO Code - 042 (San Juan)">RDO Code - 042 (San Juan)</option>
                                        <option value="RDO Code - 043 (Pasig)">RDO Code - 043 (Pasig)</option>
                                        <option value="RDO Code - 044 (Taguig-Pateros)">RDO Code - 044 (Taguig-Pateros)</option>
                                        <option value="RDO Code - 045 (Marikina)">RDO Code - 045 (Marikina)</option>
                                        <option value="RDO Code - 046 (Cainta-Taytay)">RDO Code - 046 (Cainta-Taytay)</option>
                                        <option value="RDO Code - 047 (East Makati)">RDO Code - 047 (East Makati)</option>
                                        <option value="RDO Code - 048 (West Makati)">RDO Code - 048 (West Makati)</option>
                                        <option value="RDO Code - 049 (North Makati)">RDO Code - 049 (North Makati)</option>
                                        <option value="RDO Code - 050 (South Makati)">RDO Code - 050 (South Makati)</option>
                                        <option value="RDO Code - 051 (Pasay City)">RDO Code - 051 (Pasay City)</option>
                                        <option value="RDO Code - 052 (Parañaque)">RDO Code - 052 (Parañaque)</option>
                                        <option value="RDO Code - 53A (Las Piñas City)">RDO Code - 53A (Las Piñas City)</option>
                                        <option value="RDO Code - 53B (Muntinlupa City)">RDO Code - 53B (Muntinlupa City)</option>
                                        <option value="RDO Code - 54A (Trece Martirez City, East Cavite)">RDO Code - 54A (Trece Martirez City, East Cavite)</option>
                                        <option value="RDO Code - 54B (Kawit, West Cavite)">RDO Code - 54B (Kawit, West Cavite)</option>
                                        <option value="RDO Code - 055 (San Pablo City)">RDO Code - 055 (San Pablo City)</option>
                                        <option value="RDO Code - 056 (Calamba, Laguna)">RDO Code - 056 (Calamba, Laguna)</option>
                                        <option value="RDO Code - 057 (Biñan, Laguna)">RDO Code - 057 (Biñan, Laguna)</option>
                                        <option value="RDO Code - 058 (Batangas City)">RDO Code - 058 (Batangas City)</option>
                                        <option value="RDO Code - 059 (Lipa City)">RDO Code - 059 (Lipa City)</option>
                                        <option value="RDO Code - 060 (Lucena City)">RDO Code - 060 (Lucena City)</option>
                                        <option value="RDO Code - 061 (Gumaca, Quezon)">RDO Code - 061 (Gumaca, Quezon)</option>
                                        <option value="RDO Code - 062 (Boac, Marinduque)">RDO Code - 062 (Boac, Marinduque)</option>
                                        <option value="RDO Code - 063 (Calapan, Oriental Mindoro)">RDO Code - 063 (Calapan, Oriental Mindoro)</option>
                                        <option value="RDO Code - 064 (Talisay, Camarines Norte)">RDO Code - 064 (Talisay, Camarines Norte)</option>
                                        <option value="RDO Code - 065 (Naga City)">RDO Code - 065 (Naga City)</option>
                                        <option value="RDO Code - 066 (Iriga City)">RDO Code - 066 (Iriga City)</option>
                                        <option value="RDO Code - 067 (Legazpi City, Albay)">RDO Code - 067 (Legazpi City, Albay)</option>
                                        <option value="RDO Code - 068 (Sorsogon, Sorsogon)">RDO Code - 068 (Sorsogon, Sorsogon)</option>
                                        <option value="RDO Code - 069 (Virac, Catanduanes)">RDO Code - 069 (Virac, Catanduanes)</option>
                                        <option value="RDO Code - 070 (Masbate, Masbate)">RDO Code - 070 (Masbate, Masbate)</option>
                                        <option value="RDO Code - 071 (Kalibo, Aklan)">RDO Code - 071 (Kalibo, Aklan)</option>
                                        <option value="RDO Code - 072 (Roxas City)">RDO Code - 072 (Roxas City)</option>
                                        <option value="RDO Code - 073 (San Jose, Antique)">RDO Code - 073 (San Jose, Antique)</option>
                                        <option value="RDO Code - 074 (Iloilo City)">RDO Code - 074 (Iloilo City)</option>
                                        <option value="RDO Code - 075 (Zarraga, Iloilo City)">RDO Code - 075 (Zarraga, Iloilo City)</option>
                                        <option value="RDO Code - 076 (Victorias City, Negros Occidental)">RDO Code - 076 (Victorias City, Negros Occidental)</option>
                                        <option value="RDO Code - 077 (Bacolod City)">RDO Code - 077 (Bacolod City)</option>
                                        <option value="RDO Code - 078 (Binalbagan, Negros Occidental)">RDO Code - 078 (Binalbagan, Negros Occidental)</option>
                                        <option value="RDO Code - 079 (Dumaguete City)">RDO Code - 079 (Dumaguete City)</option>
                                        <option value="RDO Code - 080 (Mandaue City)">RDO Code - 080 (Mandaue City)</option>
                                        <option value="RDO Code - 081 (Cebu City North)">RDO Code - 081 (Cebu City North)</option>
                                        <option value="RDO Code - 082 (Cebu City South)">RDO Code - 082 (Cebu City South)</option>
                                        <option value="RDO Code - 083 (Talisay City, Cebu)">RDO Code - 083 (Talisay City, Cebu)</option>
                                        <option value="RDO Code - 084 (Tagbilaran City)">RDO Code - 084 (Tagbilaran City)</option>
                                        <option value="RDO Code - 085 (Catarman, Northern Samar)">RDO Code - 085 (Catarman, Northern Samar)</option>
                                        <option value="RDO Code - 086 (Borongan, Eastern Samar)">RDO Code - 086 (Borongan, Eastern Samar)</option>
                                        <option value="RDO Code - 087 (Calbayog City, Samar)">RDO Code - 087 (Calbayog City, Samar)</option>
                                        <option value="RDO Code - 088 (Tacloban City)">RDO Code - 088 (Tacloban City)</option>
                                        <option value="RDO Code - 089 (Ormoc City)">RDO Code - 089 (Ormoc City)</option>
                                        <option value="RDO Code - 090 (Maasin, Southern Leyte)">RDO Code - 090 (Maasin, Southern Leyte)</option>
                                        <option value="RDO Code - 091 (Dipolog City)">RDO Code - 091 (Dipolog City)</option>
                                        <option value="RDO Code - 092 (Pagadian City, Zamboanga del Sur)">RDO Code - 092 (Pagadian City, Zamboanga del Sur)</option>
                                        <option value="RDO Code - 093A (Zamboanga City, Zamboanga del Sur)">RDO Code - 093A (Zamboanga City, Zamboanga del Sur)</option>
                                        <option value="RDO Code - 093B (Ipil, Zamboanga Sibugay)">RDO Code - 093B (Ipil, Zamboanga Sibugay)</option>
                                        <option value="RDO Code - 094 (Isabela, Basilan)">RDO Code - 094 (Isabela, Basilan)</option>
                                        <option value="RDO Code - 095 (Jolo, Sulu)">RDO Code - 095 (Jolo, Sulu)</option>
                                        <option value="RDO Code - 096 (Bongao, Tawi-Tawi)">RDO Code - 096 (Bongao, Tawi-Tawi)</option>
                                        <option value="RDO Code - 097 (Gingoog City)">RDO Code - 097 (Gingoog City)</option>
                                        <option value="RDO Code - 098 (Cagayan de Oro City)">RDO Code - 098 (Cagayan de Oro City)</option>
                                        <option value="RDO Code - 099 (Malaybalay City, Bukidnon)">RDO Code - 099 (Malaybalay City, Bukidnon)</option>
                                        <option value="RDO Code - 100 (Ozamis City)">RDO Code - 100 (Ozamis City)</option>
                                        <option value="RDO Code - 101 (Iligan City)">RDO Code - 101 (Iligan City)</option>
                                        <option value="RDO Code - 102 (Marawi City)">RDO Code - 102 (Marawi City)</option>
                                        <option value="RDO Code - 103 (Butuan City)">RDO Code - 103 (Butuan City)</option>
                                        <option value="RDO Code - 104 (Bayugan City, Agusan del Sur)">RDO Code - 104 (Bayugan City, Agusan del Sur)</option>
                                        <option value="RDO Code - 105 (Surigao City)">RDO Code - 105 (Surigao City)</option>
                                        <option value="RDO Code - 106 (Tandag, Surigao del Sur)">RDO Code - 106 (Tandag, Surigao del Sur)</option>
                                        <option value="RDO Code - 107 (Cotabato City)">RDO Code - 107 (Cotabato City)</option>
                                        <option value="RDO Code - 108 (Kidapawan, North Cotabato)">RDO Code - 108 (Kidapawan, North Cotabato)</option>
                                        <option value="RDO Code - 109 (Tacurong, Sultan Kudarat)">RDO Code - 109 (Tacurong, Sultan Kudarat)</option>
                                        <option value="RDO Code - 110 (General Santos City)">RDO Code - 110 (General Santos City)</option>
                                        <option value="RDO Code - 111 (Koronadal City, South Cotabato)">RDO Code - 111 (Koronadal City, South Cotabato)</option>
                                        <option value="RDO Code - 112 (Tagum, Davao del Norte)">RDO Code - 112 (Tagum, Davao del Norte)</option>
                                        <option value="RDO Code - 113A (West Davao City)">RDO Code - 113A (West Davao City)</option>
                                        <option value="RDO Code - 113B (East Davao City)">RDO Code - 113B (East Davao City)</option>
                                        <option value="RDO Code - 114 (Mati, Davao Oriental)">RDO Code - 114 (Mati, Davao Oriental)</option>
                                        <option value="RDO Code - 115 (Digos, Davao del Sur)">RDO Code - 115 (Digos, Davao del Sur)</option>
                                    </select>
                                    {{-- <small class="text-gray-500 absolute left-0 mt-2">
                                        Businesses with RDO Nos. 116, 125, 126, 121, 124, 123, 127, etc., are considered large taxpayers. <br/> As such, they will be monitored by the National Office, specifically by the Large Taxpayer’s Division.
                                    </small> --}}
                                </div>
                            </div>
                        </div>
                
                        {{-- Tax Type --}}
                        <div class="flex flex-col w-1/2 pl-4">
                            <p class="p-10 text-zinc-600 font-medium text-lg mb-4">Tax type organization complies with?</p>
                            <div class="flex flex-col gap-2 justify-center items-center">
                                <label for="percentage_tax" class="flex w-80 min-w-[14rem] cursor-pointer items-center justify-start gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 font-medium text-slate-700">
                                    <input type="radio" name="tax_type" id="percentage_tax" wire:model="tax_type" value="Percentage Tax" class="relative h-4 w-4 appearance-none rounded-full border border-gray-200 bg-white checked:border-blue-900 checked:bg-blue-900 focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-slate-800">
                                    <span class="text-sm">Percentage Tax</span>
                                </label>
                                <label for="value_added_tax" class="flex w-80 min-w-[14rem] cursor-pointer items-center justify-start gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 font-medium text-slate-700">
                                    <input type="radio" name="tax_type" id="value_added_tax" wire:model="tax_type" value="Value-Added Tax" class="relative h-4 w-4 appearance-none rounded-full border border-gray-200 bg-white checked:border-blue-900 checked:bg-blue-900 focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-slate-800">
                                    <span class="text-sm">Value-Added Tax</span>
                                </label>
                                <label for="tax_exempt" class="flex w-80 min-w-[14rem] cursor-pointer items-center justify-start gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 font-medium text-slate-700">
                                    <input type="radio" name="tax_type" id="tax_exempt" wire:model="tax_type" value="Tax Exempt" class="relative h-4 w-4 appearance-none rounded-full border border-gray-200 bg-white checked:border-blue-900 checked:bg-blue-900 focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-slate-800">
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
                                <div class="relative max-w-sm">
                                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                        </svg>
                                    </div>
                                    <input name="start_date" id="start_date" wire:model="start_date" id="datepicker-format" datepicker datepicker-min-date="06/04/2024" datepicker-max-date="05/05/2025" class="border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-900 focus:border-blue-900 block w-full ps-10 p-2.5" placeholder="Enter Start Date">
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col mb-2 w-80">
                            <div class="flex flex-col">
                                <x-field-label for="registration_date" value="{{ __('Registration Date') }}" class="mb-2 text-left" />
                                <div class="relative max-w-sm">
                                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                        </svg>
                                    </div>
                                    <input name="registration_date" id="registration_date" wire:model="registration_date" id="datepicker-format" datepicker datepicker-min-date="06/04/2024" datepicker-max-date="05/05/2025" class="border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-900 focus:border-blue-900 block w-full ps-10 p-2.5" placeholder="Enter Registration Date">
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col mb-2 w-80">
                            <div class="flex flex-col">
                                <x-field-label for="financial_year_end" value="{{ __('Financial Year End') }}" class="mb-2 text-left" />
                                <select name="financial_year_end" id="financial_year_end" wire:model="financial_year_end" class="border rounded-xl px-4 py-2 w-full mb-4 border-gray-300 placeholder:text-gray-400 placeholder:font-light placeholder:text-sm focus:border-slate-500 focus:ring-slate-500 shadow-sm">
                                    <option value="">Select Financial Year End</option>
                                    <option value="12/31/2024">December 31</option>
                                    <!-- idk where to find other selections here-->
                                </select>
                            </div>
                        </div>
                        
                    </div>
                </div>
            
                <!-- Back and Next Buttons -->
                <div class="absolute inset-x-20 bottom-1 flex justify-between px-4">
                    <button id="prevBtn" class="border border-blue-900 bg-white text-blue-900 font-bold px-4 py-2 rounded-xl disabled:opacity-50">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5" viewBox="0 0 16 16">
                                <path fill="#1e3a8a" fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m10.25.75a.75.75 0 0 0 0-1.5H6.56l1.22-1.22a.75.75 0 0 0-1.06-1.06l-2.5 2.5a.75.75 0 0 0 0 1.06l2.5 2.5a.75.75 0 1 0 1.06-1.06L6.56 8.75z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                        Go back
                    </button>
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
                </div>

                <div x-show="showModal" x-data="{ showModal: false }" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-30">
                    <div class="bg-white rounded-lg shadow-lg p-6 text-center max-w-lg w-full">
                        <!-- Centered Image -->
                        <div class="flex justify-center mb-4">
                            <img src="{{ asset('images/Ok-amico.png') }}" alt="Organization Created" class="w-40 h-40 mr-6">
                        </div>
                        <h2 class="text-emerald-500 font-bold text-3xl whitespace-normal mb-4">Organization Added</h2>
                        <p class="font-normal text-sm mb-4">The organization has been successfully<br>added! Go back to the portal to open<br/> and start the session.</p>
                        <div class="flex items-center justify-center mt-4 mb-4">
                            <button type="button" onclick="window.location.href='{{ route('org-setup') }}'" @click="showModal = false" class="inline-flex items-center w-48 justify-center py-2 bg-emerald-500 border border-transparent rounded-xl 
                            font-bold text-sm text-white tracking-widest hover:bg-emerald-600 focus:bg-emerald-700 active:bg-emerald-700 focus:outline-none disabled:opacity-50 transition ease-in-out duration-150">
                                {{ __('Go Back to Portal') }} 
                                <div class="ms-2 w-5 h-5 flex items-center justify-center border-2 border-white rounded-full">
                                    <svg class="rtl:rotate-180 w-3.5 h-3.5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                    </svg>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <button type = "submit"></button>
        </form>
    </div>

    {{-- Simple remedy muna nilagay ko kapag mag aappear na "Save" button sa Financial Settings. --}}
    {{-- kasi nag search ako, puro pang controller pinakita --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.tab');
        const tabContents = document.querySelectorAll('.tab-content-item');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const saveBtnHtml = `
            <button type="submit" id="saveBtn" class="bg-blue-900 text-white font-semibold px-4 py-2 rounded-xl" @click="showModal = true">
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

        function updateButtonsVisibility() {
            const activeTabId = tabs[currentTab].id;
            if (activeTabId === 'tab-financial') {
                nextBtn.outerHTML = saveBtnHtml;
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
                }
            }
        }

        function activateTab(tabId) {
            tabContents.forEach(item => item.classList.add('hidden'));
            switch (tabId) {
                case 'tab-class':
                    tabContents[0].classList.remove('hidden');
                    break;
                case 'tab-bg':
                    tabContents[1].classList.remove('hidden');
                    break;
                case 'tab-add':
                    tabContents[2].classList.remove('hidden');
                    break;
                case 'tab-contact':
                    tabContents[3].classList.remove('hidden');
                    break;
                case 'tab-tax':
                    tabContents[4].classList.remove('hidden');
                    break;
                case 'tab-financial':
                    tabContents[5].classList.remove('hidden');
                    break;
            }
            updateButtonsVisibility();
        }

        function updateTabs() {
            tabs.forEach((tab, index) => {
                tab.classList.remove('text-blue-900', 'font-semibold', 'border-b-4', 'border-blue-900', 'rounded-t-lg');
                tab.classList.add('text-gray-500');
                tabContents[index].classList.add('hidden');
                
                if (index === currentTab) {
                    tab.classList.add('text-blue-900', 'font-semibold', 'border-b-4', 'border-blue-900', 'rounded-t-lg');
                    tab.classList.remove('text-gray-500');
                    tabContents[index].classList.remove('hidden');
                }
            });
            prevBtn.disabled = currentTab === 0;
            nextBtn.disabled = currentTab === tabs.length - 1;
            updateButtonsVisibility(); 
        }
        prevBtn.addEventListener('click', () => {
            if (currentTab > 0) {
                currentTab--;
                updateTabs();
            }
        });
        nextBtn.addEventListener('click', () => {
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
        updateTabs();
    });
        
    </script>

</x-organization-layout>
    {{-- </body>
</html> --}}