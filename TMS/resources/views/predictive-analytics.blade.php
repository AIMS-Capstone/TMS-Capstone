<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{-- <x-predictive-analytics-main /> --}}
                <div class="container mx-auto my-4 pt-6">
                    <div class="px-10">
                        <div class="flex flex-row w-full items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" viewBox="0 0 24 24">
                                <path fill="#1e3a8a" d="M4 13h6c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v8c0 .55.45 1 1 1m0 8h6c.55 0 1-.45 1-1v-4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v4c0 .55.45 1 1 1m10 0h6c.55 0 1-.45 1-1v-8c0-.55-.45-1-1-1h-6c-.55 0-1 .45-1 1v8c0 .55.45 1 1 1M13 4v4c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1h-6c-.55 0-1 .45-1 1"/>
                            </svg>
                            <span><p class="font-bold text-3xl text-left taxuri-color">Predictive Analytics</p></span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center px-10">
                        <div class="flex items-center px-2">            
                            <p class="font-normal text-sm taxuri-text">The Predictive Analytics feature helps you look ahead by analyzing<br/>your financial data to forecast trends and outcomes.</p>
                        </div>
                        <div class="items-end float-end relative sm:w-auto" >
                            <button type="button" class="flex items-center text-white font-medium bg-blue-900 rounded-lg text-sm px-5 py-2.5 me-2 mb-2 hover:bg-blue-950 focus:outline-none focus:ring-2 focus:ring-zinc-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 w-4 h-4" viewBox="0 0 24 24">
                                    <path fill="none" stroke="#ffffff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4m4-5l5 5l5-5m-5 5V3"/>
                                </svg>
                                Download Report
                            </button>
                        </div>
                    </div>  
                    <hr class="mt-10">
                </div> 
                
                <div class="container mx-auto my-2 pt-4 px-10">
                    <h1 class="taxuri-color font-bold">Overview and Key Metrics</h1>
                    <div class="mt-4 align-middle items-center grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-10">
                        <div class="bg-white border rounded-lg p-4 text-left">
                            <p class="text-4xl font-bold text-blue-900 text-left">₱{{ number_format($totalRevenueCollected, 2) }}</p>
                            <p class="text-zinc-600 text-left font-bold">Total Revenue Collected</p>
                            <p class="text-zinc-500 text-sm text-left mb-2">Total of all revenue collected from your business activities</p>
                            <span class="bg-blue-100 text-left text-blue-800 rounded-full px-3 py-1 text-xs mt-4">Monthly</span>
                        </div>
                        
                        <div class="bg-white border rounded-lg p-4 text-left">
                            <p class="text-4xl font-bold text-blue-900 text-left">₱{{ number_format($totalTaxPaid, 2) }}</p>
                            <p class="text-zinc-600 text-left font-bold">Total Tax Paid</p>
                            <p class="text-zinc-500 text-sm text-left mb-2">Total amount of taxes that have been paid by the business</p>
                            <span class="bg-blue-100 text-left text-blue-800 rounded-full px-3 py-1 text-xs mt-4">Monthly</span>
                        </div>
                        
                        <div class="bg-white border rounded-lg p-4 text-left">
                            <p class="text-4xl font-bold text-blue-900 text-left">{{ number_format($totalPurchasesMade) }}</p>
                            <p class="text-zinc-600 text-left font-bold">Total Purchases Made</p>
                            <p class="text-zinc-500 text-sm text-left mb-2">Total amount of purchases made by the business</p>
                            <span class="bg-blue-100 text-left text-blue-800 rounded-full px-3 py-1 text-xs mt-4">Monthly</span>
                        </div>
                        
                        <div class="bg-white border rounded-lg p-4 text-left">
                            <p class="text-4xl font-bold text-blue-900 text-left">₱{{ number_format($totalCostOfPurchases, 2) }}</p>
                            <p class="text-zinc-600 text-left font-bold">Total Cost of Purchase</p>
                            <p class="text-zinc-500 text-sm text-left mb-2">The cost associated with the purchase made</p>
                            <span class="bg-blue-100 text-left text-blue-800 rounded-full px-3 py-1 text-xs mt-4">Monthly</span>
                        </div>
                    </div>
                <div>
                
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-[2fr_1fr] gap-10 mb-10">
                    {{-- Chart div --}}
                    <div class="bg-white border rounded-lg h-72 w-[750px] p-6">
                        <h1 class="taxuri-text font-bold flex items-center">Monthly Distribution
                            <span class="ml-2 relative group">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                    <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2"/>
                                </svg>
                                <div class="absolute p-4 left-full ml-2 mt-0 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    This graph visually represents the distribution of taxes paid each month to monitor tax trends and fluctuations across the fiscal year.
                                </div>
                            </span>
                        </h1>
                        {{-- Chart --}}
                        <canvas id="lineChart" width="700" height="200"></canvas>
                    </div>
                
                    {{-- Bar graph div --}}
                    <div class="bg-white border rounded-lg w-[345px] h-72 p-6 relative">
                        <h1 class="taxuri-text font-bold flex items-center">Monthly Revenue Distribution
                            <span class="ml-2 relative group">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer group-hover:opacity-100">
                                    <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2"/>
                                </svg>
                                <div class="absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg opacity-0 transition-opacity duration-200 tooltip group-hover:opacity-100">
                                    This graph shows the distribution of revenue across different sources or categories.
                                </div>
                            </span>
                        </h1>
                        {{-- Bar chart --}}
                        <canvas id="barChart" width="300" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white shadow-sm sm:rounded-lg p-10">
                <!-- Header -->
                <div class="flex items-center space-x-2 mb-4">
                    <h1 class="taxuri-color font-bold">TAXURI Projections</h1>
                </div>
    
                <!-- Projections Container -->
                <div class="grid border rounded-lg grid-cols-3 p-6 gap-8 align-middle">
                    <!-- Projected Quarterly Revenue -->
                    <div class="text-left ml-4 relative pr-6 text-zinc-700">
                        <h2 class="font-semibold  flex items-center">
                            Projected Purchases Made
                            <span class="ml-2 group relative">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer group-hover:opacity-100">
                                    <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2"/>
                                </svg>
                                <div class="absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg opacity-0 transition-opacity duration-200 tooltip group-hover:opacity-100">
                                    This provides a forecast of expected revenue for the upcoming quarters.
                                </div>
                            </span>
                        </h2>
                        @foreach ($predictions['projected_quarterly_purchase_count'] as $index => $cost)
                        <p class="text-sm">Quarter {{ $index + 1 }} - {{ number_format($cost, 2) }}</p>
                    @endforeach
                        
                        <div class="absolute top-4 right-0 h-3/4 w-px bg-gray-300"></div>
                    </div>
                
                    <!-- Projected Purchases Made -->
                    <div class="text-left relative pr-6 text-zinc-700 ">
                        <h2 class="font-semibold flex items-center">
                            Projected Cost of Purchase
                            <span class="ml-2 group relative">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer group-hover:opacity-100">
                                    <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2"/>
                                </svg>
                                <div class="absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg opacity-0 transition-opacity duration-200 tooltip group-hover:opacity-100">
                                    This provides an estimate of the total purchases your business is likely to make in future periods.
                                </div>
                            </span>
                        </h2>
                        @foreach ($predictions['projected_quarterly_purchase_cost'] as $index => $cost)
                            <p class="text-sm">Quarter {{ $index + 1 }} - ₱ {{ number_format($cost, 2) }}</p>
                        @endforeach
                        
                        <div class="absolute top-4 right-0 h-3/4 w-px bg-gray-300"></div>
                    </div>
                
                    <!-- Projected Sales Revenue -->
                    <div class="text-left text-zinc-700">
                        <h2 class="font-semibold flex items-center">
                            Projected Sales Revenue
                            <span class="ml-2 group relative">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer group-hover:opacity-100">
                                    <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2"/>
                                </svg>
                                <div class="absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg opacity-0 transition-opacity duration-200 tooltip group-hover:opacity-100">
                                    This data shows the projected sales figures, giving insight into the potential growth in revenue over the next few quarters.
                                </div>
                            </span>
                        </h2>
                        @foreach ($predictions['projected_quarterly_sales_revenue'] as $index => $revenue)
                            <p class="text-sm">Quarter {{ $index + 1 }} - ₱ {{ number_format($revenue, 2) }}</p>
                        @endforeach
                    </div>

                </div>

                <div class="grid grid-cols-3 gap-10 mt-6">
                    <!-- Purchases Tax Distribution Chart -->
                    <div class="bg-white border rounded-lg p-6 h-[345px]">
                        <h2 class="font-semibold text-left text-zinc-700 flex items-center">
                            Purchases Tax Distribution
                            <span class="ml-2 group relative">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer group-hover:opacity-100">
                                    <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2"/>
                                </svg>
                                <div class="absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg opacity-0 transition-opacity duration-200 tooltip group-hover:opacity-100">
                                    This graph provides a visual distribution of the taxes paid on purchases.
                                </div>
                            </span>
                        </h2>
                        <canvas id="donutChart" width="300" height="200"></canvas>
                    </div>
                
                    <div class="col-span-2 grid grid-cols-2 gap-10">
                        <div class="grid grid-rows-2 gap-4">
                            <!-- Projected End-of-Year Tax Liability -->
                            <div class="bg-white border rounded-lg p-6 text-left h-36"> 
                                <h2 class="text-4xl font-bold text-left taxuri-color flex items-center">
                                    ₱ {{ number_format($predictions['projected_end_of_year_tax'], 2) }}
                                </h2>
                                <h2 class="font-semibold text-left text-zinc-700 flex items-center">
                                    Projected End-of-Year Tax Liability
                                    <span class="ml-2 group relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer group-hover:opacity-100">
                                            <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2"/>
                                        </svg>
                                        <div class="absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg opacity-0 transition-opacity duration-200 tooltip group-hover:opacity-100">
                                            This forecast estimates your business’s tax liability for the entire year.
                                        </div>
                                    </span>
                                </h2>
                                <span class="mt-4 bg-blue-100 text-blue-900 text-xs py-1 px-2 rounded-full">Full Year Projection</span>
                            </div>
                        
                     <!-- Quarterly Tax Estimates -->
<div class="bg-white border rounded-lg p-6 text-zinc-700 h-36 mt-5">
    <h2 class="font-semibold text-left flex items-center">
        Quarterly Tax Estimates
        <span class="ml-2 group relative">
            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer group-hover:opacity-100">
                <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2"/>
            </svg>
            <div class="absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg opacity-0 transition-opacity duration-200 tooltip group-hover:opacity-100">
                This provides tax estimates for each quarter
            </div>
        </span>
    </h2>
    @foreach ($predictions['projected_quarterly_tax_estimate'] as $index => $estimate)
        <p class="text-sm">Quarter {{ $index + 1 }} - ₱ {{ number_format($estimate, 2) }}</p>
    @endforeach
</div>

                        </div>
                
                        <div class="flex justify-center items-center w-52 h-[345px]">
                            <div class="bg-blue-50 rounded-lg p-4 text-center">
                                <div class="flex justify-center">
                                    <img src="images/Visual data-pana.png" class="object-contain h-40" />
                                </div>
                                <p class="text-sm text-zinc-700 mt-4">
                                    <b class="taxuri-color">TAXURI</b> is designed to do more than just keep track of your financials—it helps you look ahead.
                                </p>
                                <div class="flex justify-center mt-4"> <!-- Added flex container to center the button -->
                                    <button class="bg-blue-900 text-white text-sm font-semibold py-2 px-4 rounded-lg flex items-center">
                                        <span class="mr-2"> <!-- Add margin to the right of the SVG for spacing -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                <path fill="none" stroke="#ffff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 11A8.1 8.1 0 0 0 4.5 9M4 5v4h4m-4 4a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/>
                                            </svg>
                                        </span>
                                        Refresh Data
                                    </button>
                                </div>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    const lineLabels = @json($labels); // Pass the PHP array of labels (months)
    
    // Preparing datasets for Chart.js
    const datasets = [];
    @foreach($chartData as $data)
        datasets.push({
            label: @json($data['label']), // Tax type label
            data: @json($data['data']), // Monthly counts
            borderColor: @json($data['borderColor']),
            backgroundColor: @json($data['backgroundColor']),
        });
    @endforeach

    const lineData = {
        labels: lineLabels,
        datasets: datasets // Pass the datasets directly
    };
// Define the chart configuration
const lineConfig = {
    type: 'line',
    data: lineData,
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                align: 'start',
                labels: {
                    usePointStyle: true,
                    pointStyle: 'circle',
                    font: {
                        family: 'Inter',
                        size: 10
                    }
                }
            }
        },
        scales: {
            x: {
                ticks: { font: { family: 'Inter' } } // X-axis ticks
            },
            y: {
                ticks: { font: { family: 'Inter' } } // Y-axis ticks
            }
        }
    }
};

// Get the context for the chart and create it
const lineCtx = document.getElementById('lineChart').getContext('2d');

// Error handling: Try to create the chart and catch any errors
try {
    new Chart(lineCtx, lineConfig);
} catch (error) {
    console.error('Error creating chart:', error); // Log any errors to the console
}

        // BAR CHART CONFIGURATION
        const barLabels = @json($barLabels); // Get the last 4 months' labels
    
const barData = {
    labels: barLabels,
    datasets: [
        {
      
            label: 'Monthly Revenue',
            data: @json($barData), // Get the revenue data for the last 4 months
            borderColor: 'rgba(30, 58, 138, 1)',
            backgroundColor: 'rgba(30, 58, 138, 1)',
        }
    ]
};

        const barConfig = {
            type: 'bar',
            data: barData,
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom',
                        align: 'start',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: {
                                family: 'Inter',
                                size: 10
                            }
                        }
                    },
                    title: { display: true, text: '' }
                }
            }
        };

        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, barConfig);
        const donutLabels = {!! json_encode($donutLabels) !!}; // Blade syntax for passing array
        const donutData = {!! json_encode($donutData) !!}; // Blade syntax for passing array
        // Donut Chart 
     

        const donutConfig = {
        type: 'doughnut',
        data: {
            labels: donutLabels, // Labels from PHP data
            datasets: [{
                label: '',
                data: donutData, // Data from PHP data
                backgroundColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 205, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(54, 162, 235, 1)',
                    // Add more colors if needed based on the number of categories
                ],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            family: 'Inter',
                            size: 12,
                        },
                    },
                },
                title: {
                    display: true,
                    text: 'Purchase Counts by Category',
                    font: {
                        family: 'Inter',
                        size: 16,
                        weight: '600',
                    },
                },
            }
        },
    };
        const donutCtx = document.getElementById('donutChart').getContext('2d');
        new Chart(donutCtx, donutConfig);
    </script>
</x-app-layout>