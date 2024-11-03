<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class PredictionController extends Controller
{
    public function getPredictions(Request $request)
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        $organizationId = session('organization_id');
        // Retrieve financial metrics (if needed for internal use, not sent to Python)
        $totalRevenueCollected = DB::table('transactions')
            ->where('transaction_type', 'Sales')
            ->where('organization_id', $organizationId)
            ->whereYear('date', $currentYear)
        ->whereMonth('date', $currentMonth)
            ->sum('total_amount');

        $totalTaxPaid = DB::table('transactions')
            ->where('Paidstatus', 'Paid')
            ->whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->where('organization_id', $organizationId)
            ->sum('vat_amount');

        $totalPurchasesMade = DB::table('transactions')
            ->where('transaction_type', 'Purchase')
            ->whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->where('organization_id', $organizationId)
            ->count();
          

        $totalCostOfPurchases = DB::table('transactions')
            ->where('transaction_type', 'Purchase')
            ->whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->where('organization_id', $organizationId)
            ->sum('total_amount');

           
            

            // Get the current year and month
         
            
            // Get tax counts for the last 12 months only
            $taxCounts = DB::table('transactions')
                ->join('tax_rows', 'transactions.id', '=', 'tax_rows.transaction_id')
                ->join('tax_types', 'tax_rows.tax_type', '=', 'tax_types.id')
                ->select(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m") as month_year'), 'tax_types.tax_type', DB::raw('COUNT(*) as count'))
                ->where('tax_types.transaction_type', 'Sales')
                ->where('organization_id', $organizationId)
                ->where('transactions.created_at', '>=', Carbon::now()->subMonths(12)) // Filter for the last 12 months
                ->groupBy(DB::raw('month_year'), 'tax_types.tax_type')
                ->orderBy('month_year')
                ->get();
            
            // Prepare chart data
            $chartData = [];
            $months = array_fill(0, 12, 0); // Initialize an array for 12 months
            
            foreach ($taxCounts as $row) {
                $monthIndex = (int)date('n', strtotime($row->month_year)) - 1; // Get month index (0 to 11)
                
                // If the row's year is different from the current year, skip it
                $rowYear = (int)date('Y', strtotime($row->month_year));
                if ($rowYear !== $currentYear) {
                    continue; // Skip counts from previous years if you only want the current year
                }
            
                // Initialize chart data if the tax type doesn't exist
                if (!isset($chartData[$row->tax_type])) {
                    $chartData[$row->tax_type] = [
                        'label' => $row->tax_type,
                        'data' => array_fill(0, 12, 0), // Fill with 0s for all months
                        'borderColor' => 'rgba(108, 159, 198, 1)',
                        'backgroundColor' => 'rgba(39, 86, 7, 0.2)',
                    ];
                }
                
                $chartData[$row->tax_type]['data'][$monthIndex] += $row->count; // Increment count for the month
            }
            
            // Add labels for the chart
            $labels = [
                "Jan", "Feb", "Mar", "Apr", "May", "Jun", 
                "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
            ];

            $purchaseCounts = DB::table('transactions')
            ->join('tax_rows', 'transactions.id', '=', 'tax_rows.transaction_id') // Join with purchase rows
            ->join('tax_types', 'tax_rows.tax_type', '=', 'tax_types.id') // Join with purchase types
            ->select('tax_types.tax_type as label', DB::raw('COUNT(*) as count'))
            ->where('tax_types.transaction_type', 'Purchase')
            ->where('organization_id', $organizationId)
            ->where('transactions.date', '>=', Carbon::now()->subMonths(12)) // Filter for the last 12 months
            ->groupBy('tax_types.tax_type') // Group by purchase type
            ->orderBy('count', 'desc') // Order by count descending
            ->get();
         

// Prepare data for the donut chart
$donutLabels = [];
$donutData = [];

foreach ($purchaseCounts as $row) {
$donutLabels[] = $row->label; // Add label
$donutData[] = $row->count; // Add count
}

// Get the last four months of transactions
$monthlyRevenueDistribution = DB::table('transactions')
    ->selectRaw('MONTH(date) as month, YEAR(date) as year, SUM(total_amount) as monthly_revenue')
    ->where('transaction_type', 'Sales')
    ->where('organization_id', $organizationId)
    ->where('date', '>=', Carbon::now()->subMonths(4)) // Ensure only the last 4 months
    ->groupBy('year', 'month') // Group by year and month
    ->orderBy('year', 'desc')
    ->orderBy('month', 'desc') // Order to get the most recent first
    ->get();

// Initialize arrays
$barData = array_fill(0, 4, 0); // Create an array for 4 months
$barLabels = [];

// Get current month and backtrack to find the last four months
$currentMonth = Carbon::now();
$recentMonths = [];

// Collect the last four month names
for ($i = 0; $i < 4; $i++) {
    $recentMonths[] = $currentMonth->subMonth()->format('F Y');
}

// Reverse the order to start from the oldest to the newest
$recentMonths = array_reverse($recentMonths);

// Fill the labels for the last four months
foreach ($recentMonths as $monthYear) {
    $barLabels[] = $monthYear;
}

// Loop through the revenue data
foreach ($monthlyRevenueDistribution as $revenue) {
    // Create a month-year string from the revenue data
    $monthYear = Carbon::createFromFormat('!m Y', $revenue->month . ' ' . $revenue->year)->format('F Y');
    
    // Search for the index of this month-year string in the barLabels array
    $monthIndex = array_search($monthYear, $barLabels);
    
    if ($monthIndex !== false) {
        $barData[$monthIndex] = $revenue->monthly_revenue; // Assign the revenue for that month
    }
}



// Now barLabels and barData should correspond correctly

        // Call the Python service to get predictions
        $response = Http::post(env('PYTHON_SERVICE_URL') . '/predict-all', [
            'organization_id' => $organizationId, // Pass organization_id to the Python service
            // Any other parameters needed for predictions can be added here
        ]);

        // Check if the response is successful
        if ($response->successful()) {
            $predictions = $response->json();
          
          
            return view('predictive-analytics', compact(
                'predictions', 
                'totalRevenueCollected', 
                'totalTaxPaid', 
                'totalPurchasesMade', 
                'totalCostOfPurchases',
                'labels',     
              'chartData',
              'barData',
              'barLabels',   
              'donutLabels',
              'donutData'   
              
              
            ));
            
        } else {
            return response()->json(['error' => 'Failed to retrieve predictions'], 500);
        }
    }
}
