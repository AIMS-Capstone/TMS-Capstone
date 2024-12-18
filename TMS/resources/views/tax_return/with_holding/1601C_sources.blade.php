@php
$organizationId = session('organization_id');
@endphp

<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Page Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h1 class="text-lg font-semibold text-gray-700">Withholding Tax Return > 1601C > Sources</h1>
                </div>

                <!-- Tabs -->
                <div class="px-6 py-4 flex space-x-4 border-b">
                    <a href="{{ route('with_holding.1601C_summary', ['id' => $with_holding->id]) }}" class="pb-2 text-blue-500 border-b-2 border-blue-500 font-semibold">Summary</a>
                    <a href="{{ route('with_holding.1601C_sources', ['id' => $with_holding->id]) }}" class="pb-2 text-gray-500 hover:text-blue-500">Sources</a>
                    <a href="{{ route('form1601C.create', ['id' => $with_holding->id]) }}" class="pb-2 text-gray-500 hover:text-blue-500">Report</a>
                    <a href="#" class="pb-2 text-gray-500 hover:text-blue-500">Notes and Activity</a>
                </div>

                <!-- Actions -->
                <div class="px-6 py-4 flex justify-between items-center">
                    <!-- Search Bar -->
                    <form action="/1601C_sources" method="GET" class="w-1/3">
                        <div class="relative">
                            <input 
                                type="search" 
                                name="search" 
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                                placeholder="Search sources..."
                            >
                            <i class="fa fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </form>

                    <!-- Action Buttons -->
                    <div class="flex space-x-4">
                            <!-- NILAGAY KO TALAGA DITO YUNG FORM, DAMING WAYS NA GINAWA KO LAHAT HINDI NAKAKAPAG TRIGGER NG SHOW KRAZY NIG -->
                            <div x-data="{ show: false, success: false }">
                                <!-- Add Button -->
                                <button 
                                    x-on:click="show = true" 
                                    class="px-4 py-2 text-sm bg-blue-500 text-white rounded-lg hover:bg-blue-600"
                                >
                                    Add
                                </button>

                                <!-- Add Modal -->
                                <div 
                                    x-show="show"
                                    x-on:close-modal.window="show = false;"   
                                    x-effect="document.body.classList.toggle('overflow-hidden', show || success)"
                                    class="fixed z-50 inset-0 flex items-center justify-center m-2 px-6"
                                    x-cloak
                                >
                                    <!-- Modal background -->
                                    <div class="fixed inset-0 bg-gray-200 opacity-50"></div>

                                    <!-- Modal container -->
                                    <div 
                                        class="bg-white rounded-lg shadow-lg w-full max-w-4xl mx-auto z-10 overflow-hidden"
                                        x-show="show" 
                                        x-transition:enter="transition ease-out duration-300 transform" 
                                        x-transition:enter-start="opacity-0 scale-90" 
                                        x-transition:enter-end="opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-200 transform" 
                                        x-transition:leave-start="opacity-100 scale-100" 
                                        x-transition:leave-end="opacity-0 scale-90"
                                    >
                                        <!-- Modal header -->
                                        <div class="bg-blue-900 text-center rounded-t-lg p-4">
                                            <h1 class="text-lg font-bold text-white">New Compensation</h1>
                                        </div>      

                                        <!-- Modal Body -->
                                        <div class="p-6 grid grid-cols-3 gap-6">
                                            <!-- Left Column: Employee Details -->
                                            <div class="col-span-2 space-y-4">
                                                <!-- Employee Selection -->
                                                <form id="add_sources" action="{{ route('with_holding.1601C_sources_store', ['id' => $with_holding->id]) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="withholding_id" value="{{ $with_holding->id }}">
                                                    <input type="hidden" id="taxable_compensation_input" name="taxable_compensation" value="0">
                                                    <div>
                                                        <label for="employee_id" class="block text-sm font-medium text-gray-700">Employee <span class="text-red-500">*</span></label>
                                                        <select 
                                                            id="employee_id" 
                                                            name="employee_id" 
                                                            class="block w-full p-2 border-gray-300 rounded-lg shadow-sm"
                                                            required
                                                        >
                                                            <option value="" disabled selected>Select Employee</option>
                                                            @foreach ($employees as $employee)
                                                                <option value="{{ $employee->id }}">
                                                                    {{ $employee->first_name }} {{ $employee->last_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <!-- Display Employee Wage Status -->
                                                    <div>
                                                        <label for="employee_wage_status" class="block text-sm font-medium text-gray-700">
                                                            Employee Wage Status
                                                        </label>
                                                        <input 
                                                            type="text" 
                                                            id="employee_wage_status" 
                                                            name="employee_wage_status" 
                                                            class="block w-full p-2 border-gray-300 rounded-lg shadow-sm bg-gray-100" 
                                                            value="{{ $employment->employee_wage_status ?? 'Not Available' }}" 
                                                            readonly
                                                        >
                                                    </div>

                                                    <!-- Payment Date -->
                                                    <div>
                                                        <label for="payment_date" class="block text-sm font-medium text-gray-700">Payment Date <span class="text-red-500">*</span></label>
                                                        <input type="date" id="payment_date" name="payment_date" class="block w-full p-2 border-gray-300 rounded-lg shadow-sm" required>
                                                    </div>

                                                    <!-- Gross Compensation -->
                                                    <div>
                                                        <label for="gross_compensation" class="block text-sm font-medium text-gray-700">Gross Compensation <span class="text-red-500">*</span></label>
                                                        <input type="number" id="gross_compensation" name="gross_compensation" step="0.01" class="block w-full p-2 border-gray-300 rounded-lg shadow-sm" required>
                                                    </div>
                                                </div>

                                                <!-- Right Column: Taxable Compensation Summary -->
                                                <div class="col-span-1 bg-gray-100 rounded-lg p-4">
                                                    <h2 class="text-lg font-semibold text-gray-800">Taxable Compensation</h2>
                                                    <p id="taxable_compensation" class="text-2xl font-bold text-blue-900 text-center mt-4">0.00</p>
                                                    <div>
                                                        <label for="tax_due" class="block text-sm font-medium text-gray-700">Tax Due</label>
                                                        <input type="number" id="tax_due" name="tax_due" step="0.01" class="w-full mt-1 p-2 border-gray-300 rounded-lg shadow-sm">
                                                    </div>
                                                </div>

                                                <!-- Full Width: Non-Taxable Compensation Details -->
                                                <div class="col-span-3 grid grid-cols-2 gap-4 mt-6">
                                                    <!-- Statutory Minimum Wage -->
                                                    <div>
                                                        <label for="statutory_minimum_wage" class="block text-sm font-medium text-gray-700">Statutory Minimum Wage <span class="text-red-500">*</span></label>
                                                        <input type="number" id="statutory_minimum_wage" name="statutory_minimum_wage" step="0.01" class="w-full mt-1 p-2 border-gray-300 rounded-lg shadow-sm" required>
                                                    </div>

                                                    <!-- Holiday Pay -->
                                                    <div>
                                                        <label for="holiday_pay" class="block text-sm font-medium text-gray-700">Holiday Pay</label>
                                                        <input type="number" id="holiday_pay" name="holiday_pay" step="0.01" class="w-full mt-1 p-2 border-gray-300 rounded-lg shadow-sm">
                                                    </div>

                                                    <!-- Overtime Pay -->
                                                    <div>
                                                        <label for="overtime_pay" class="block text-sm font-medium text-gray-700">Overtime Pay</label>
                                                        <input type="number" id="overtime_pay" name="overtime_pay" step="0.01" class="w-full mt-1 p-2 border-gray-300 rounded-lg shadow-sm">
                                                    </div>

                                                    <!-- Night Shift Differential -->
                                                    <div>
                                                        <label for="night_shift_differential" class="block text-sm font-medium text-gray-700">Night Shift Differential</label>
                                                        <input type="number" id="night_shift_differential" name="night_shift_differential" step="0.01" class="w-full mt-1 p-2 border-gray-300 rounded-lg shadow-sm">
                                                    </div>

                                                    <!-- Hazard Pay -->
                                                    <div>
                                                        <label for="hazard_pay" class="block text-sm font-medium text-gray-700">Hazard Pay</label>
                                                        <input type="number" id="hazard_pay" name="hazard_pay" step="0.01" class="w-full mt-1 p-2 border-gray-300 rounded-lg shadow-sm">
                                                    </div>

                                                    <!-- 13th Month Pay -->
                                                    <div>
                                                        <label for="month_13_pay" class="block text-sm font-medium text-gray-700">13th Month Pay and Other Benefits</label>
                                                        <input type="number" id="month_13_pay" name="month_13_pay" step="0.01" class="w-full mt-1 p-2 border-gray-300 rounded-lg shadow-sm">
                                                    </div>

                                                    <!-- De Minimis Benefits -->
                                                    <div>
                                                        <label for="de_minimis_benefits" class="block text-sm font-medium text-gray-700">De Minimis Benefits</label>
                                                        <input type="number" id="de_minimis_benefits" name="de_minimis_benefits" step="0.01" class="w-full mt-1 p-2 border-gray-300 rounded-lg shadow-sm">
                                                    </div>

                                                    <!-- SSS, GSIS, PHIC, HDMF, Union Dues -->
                                                    <div>
                                                        <label for="sss_gsis_phic_hdmf_union_dues" class="block text-sm font-medium text-gray-700">SSS, GSIS, PHIC, HDMF, Union Dues</label>
                                                        <input type="number" id="sss_gsis_phic_hdmf_union_dues" name="sss_gsis_phic_hdmf_union_dues" step="0.01" class="w-full mt-1 p-2 border-gray-300 rounded-lg shadow-sm">
                                                    </div>

                                                    <!-- Other Non-Taxable Compensation -->
                                                    <div>
                                                        <label for="other_non_taxable_compensation" class="block text-sm font-medium text-gray-700">Other Non-Taxable Compensation</label>
                                                        <input type="number" id="other_non_taxable_compensation" name="other_non_taxable_compensation" step="0.01" class="w-full mt-1 p-2 border-gray-300 rounded-lg shadow-sm">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal footer -->
                                            <div class="flex justify-end p-6 border-t border-gray-200">
                                                <button 
                                                    type="button" 
                                                    x-on:click="show = false" 
                                                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 mr-2"
                                                >
                                                    Cancel
                                                </button>
                                                <button 
                                                    type="submit" 
                                                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600"
                                                >
                                                    Save
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                        <!-- Livewire Source Import -->
                        <livewire:source-import :withholdingId="$with_holding->id" />

                        <button class="px-4 py-2 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600">
                            Delete
                        </button>
                        <button class="px-4 py-2 text-sm bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                            Download
                        </button>
                    </div>
                </div>

                <!-- Sources Table -->
                <div class="px-6 py-4 overflow-x-auto">
                    <table class="w-full border-collapse min-w-[500px]">
                        <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Employee</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Wage Status</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Payment Date</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Gross Compensation</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Tax Due</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Statutory Minimum Wage</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Holiday Pay, OT Pay, etc.</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">13th Month Pay and Other Benefits</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">De Minimis Benefits</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">SSS, GSIS, PHIC, HDMF etc.</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Other Non-Taxable Compensation</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Total Non-Taxable Compensation</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Taxable Compensation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sources as $source)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    {{ $source->employee->first_name ?? 'N/A' }} {{ $source->employee->last_name ?? '' }}
                                </td>
                                <td class="py-3 px-4">{{ $source->employment->employee_wage_status ?? 'N/A' }}</td>
                                <td class="py-3 px-4">{{ $source->payment_date ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-right">{{ number_format($source->gross_compensation, 2) ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-right">{{ number_format($source->tax_due, 2) ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-right">{{ number_format($source->statutory_minimum_wage, 2) ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-right">
                                    {{ number_format($source->holiday_pay + $source->overtime_pay + $source->night_shift_differential + $source->hazard_pay, 2) ?? 'N/A' }}
                                </td>
                                <td class="py-3 px-4 text-right">{{ number_format($source->month_13_pay, 2) ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-right">{{ number_format($source->de_minimis_benefits, 2) ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-right">{{ number_format($source->sss_gsis_phic_hdmf_union_dues, 2) ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-right">{{ number_format($source->other_non_taxable_compensation, 2) ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-right">
                                    {{ number_format($source->holiday_pay + $source->overtime_pay + $source->night_shift_differential + $source->hazard_pay + $source->statutory_minimum_wage + $source->month_13_pay + $source->de_minimis_benefits + $source->sss_gsis_phic_hdmf_union_dues + $source->other_non_taxable_compensation, 2) ?? 'N/A' }}
                                </td>
                                <td class="py-3 px-4 text-right">{{ number_format($source->taxable_compensation, 2) ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="py-3 px-4 text-center text-gray-500">
                                    No sources found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4">
                    {{ $sources->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.getElementById('employee_id').addEventListener('change', function () {
        const selectedEmployeeId = this.value;
        const employees = @json($employees);
        const selectedEmployee = employees.find(employee => employee.id == selectedEmployeeId);

        const wageStatusInput = document.getElementById('employee_wage_status');
        const statutoryFields = [
            document.getElementById('statutory_minimum_wage'),
            document.getElementById('holiday_pay'),
            document.getElementById('overtime_pay'),
            document.getElementById('night_shift_differential'),
            document.getElementById('hazard_pay'),
        ];
        const taxDueField = document.getElementById('tax_due');

        if (selectedEmployee && selectedEmployee.latest_employment) {
            const wageStatus = selectedEmployee.latest_employment.employee_wage_status || 'N/A';
            wageStatusInput.value = wageStatus;

            // Enable/disable fields based on wage status
            if (wageStatus === 'Minimum Wage') {
                statutoryFields.forEach(field => field.disabled = false); // Enable for MWEs
                taxDueField.disabled = true; // Tax-exempt for MWEs
                taxDueField.value = 0; // Reset tax due to zero for MWEs
            } else if (wageStatus === 'Above Minimum Wage') {
                statutoryFields.forEach(field => field.disabled = true); // Disable for AMWEs
                taxDueField.disabled = false; // Taxable for AMWEs
            } else {
                // Default fallback if wage status is not recognized
                statutoryFields.forEach(field => field.disabled = true);
                taxDueField.disabled = false;
            }
        } else {
            wageStatusInput.value = 'N/A';
            statutoryFields.forEach(field => field.disabled = true);
            taxDueField.disabled = false;
        }
    });

    // Additional logic to calculate taxable compensation and tax due dynamically
    document.addEventListener("DOMContentLoaded", () => {
        const fields = [
            "gross_compensation",
            "statutory_minimum_wage",
            "holiday_pay",
            "overtime_pay",
            "night_shift_differential",
            "hazard_pay",
            "month_13_pay",
            "de_minimis_benefits",
            "sss_gsis_phic_hdmf_union_dues",
            "other_non_taxable_compensation"
        ];

        const calculateCompensation = () => {
            const grossCompensation = parseFloat(document.getElementById("gross_compensation").value) || 0;
            const statutoryMinWage = parseFloat(document.getElementById("statutory_minimum_wage").value) || 0;
            const holidayPay = parseFloat(document.getElementById("holiday_pay").value) || 0;
            const overtimePay = parseFloat(document.getElementById("overtime_pay").value) || 0;
            const nightShiftDiff = parseFloat(document.getElementById("night_shift_differential").value) || 0;
            const hazardPay = parseFloat(document.getElementById("hazard_pay").value) || 0;
            const month13Pay = parseFloat(document.getElementById("month_13_pay").value) || 0;
            const deMinimisBenefits = parseFloat(document.getElementById("de_minimis_benefits").value) || 0;
            const sssGsisPhilhealth = parseFloat(document.getElementById("sss_gsis_phic_hdmf_union_dues").value) || 0;
            const otherNonTaxableComp = parseFloat(document.getElementById("other_non_taxable_compensation").value) || 0;

            const nonTaxableBenefits = statutoryMinWage + holidayPay + overtimePay + nightShiftDiff + hazardPay +
                month13Pay + deMinimisBenefits + sssGsisPhilhealth + otherNonTaxableComp;

            const taxableCompensation = grossCompensation - nonTaxableBenefits;

            // Update Taxable Compensation UI
            document.getElementById("taxable_compensation").innerText = taxableCompensation.toFixed(2);
            document.getElementById("taxable_compensation_input").value = taxableCompensation.toFixed(2);

            // Calculate Tax Due for AMWEs
            const taxDueField = document.getElementById('tax_due');
            const wageStatus = document.getElementById('employee_wage_status').value;
            if (wageStatus === 'Above Minimum Wage' && taxableCompensation > 0) {
                const taxDue = computeTax(taxableCompensation);
                taxDueField.value = taxDue.toFixed(2);
            } else {
                taxDueField.value = 0; // MWEs or zero taxable compensation
            }
        };

        // Attach event listeners to input fields
        fields.forEach(field => {
            document.getElementById(field).addEventListener("input", calculateCompensation);
        });
    });

    // Tax computation logic based on TRAIN law
    const computeTax = (taxableCompensation) => {
        const taxBrackets = [
            { limit: 250000, rate: 0 },
            { limit: 400000, rate: 0.15 },
            { limit: 800000, rate: 0.20 },
            { limit: 2000000, rate: 0.25 },
            { limit: 8000000, rate: 0.30 },
        ];

        let taxDue = 0;
        for (let i = taxBrackets.length - 1; i >= 0; i--) {
            if (taxableCompensation > taxBrackets[i].limit) {
                taxDue += (taxableCompensation - taxBrackets[i].limit) * taxBrackets[i].rate;
                taxableCompensation = taxBrackets[i].limit;
            }
        }
        return taxDue;
    };
</script>
