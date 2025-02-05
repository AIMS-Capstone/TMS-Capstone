<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class PredictionController extends Controller
{
    /**
     * Handle the incoming request for predictions
     */
    public function getPredictions(Request $request)
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $taxReturnType = $request->input('tax_return_type', '2550q');
        $organizationId = session('organization_id');

        // Gather all necessary data
        $data = $this->gatherPredictionData($organizationId, $currentYear, $currentMonth, $taxReturnType);
        
        // Get predictions from Python service
        $pythonResponse = Http::post(env('PYTHON_SERVICE_URL') . '/predict-all', [
            'organization_id' => $organizationId,
            'tax_return_type' => $taxReturnType,
            'tax_return_data' => $data['taxReturnData']->toArray()
        ]);

        if (!$pythonResponse->successful()) {
            return $request->ajax() 
                ? response()->json(['error' => 'Failed to retrieve predictions'], 500)
                : back()->with('error', 'Failed to retrieve predictions');
        }

        $predictions = $pythonResponse->json();

        // Handle AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'predictions' => $predictions,
                'success' => true
            ]);
        }

        // For regular requests, return the full view
        return view('predictive-analytics', array_merge(
            $data,
            ['predictions' => $predictions]
        ));
    }

    /**
     * Gather all necessary data for predictions
     */
    private function gatherPredictionData($organizationId, $currentYear, $currentMonth, $taxReturnType)
    {
        return [
            'totalRevenueCollected' => $this->getTotalRevenueCollected($organizationId, $currentYear, $currentMonth),
            'totalTaxPaid' => $this->getTotalTaxPaid($organizationId, $currentYear, $currentMonth),
            'totalPurchasesMade' => $this->getTotalPurchasesMade($organizationId, $currentYear, $currentMonth),
            'totalCostOfPurchases' => $this->getTotalCostOfPurchases($organizationId, $currentYear, $currentMonth),
            'taxReturnData' => $this->getTaxReturnData($organizationId, $taxReturnType),
            'labels' => $this->getMonthLabels(),
            'chartData' => $this->getChartData($organizationId, $currentYear),
            'barData' => $this->getBarChartData($organizationId),
            'barLabels' => $this->getBarChartLabels(),
            'donutLabels' => $this->getDonutChartLabels($organizationId),
            'donutData' => $this->getDonutChartData($organizationId)
        ];
    }

    /**
     * Get total revenue collected
     */
    private function getTotalRevenueCollected($organizationId, $currentYear, $currentMonth)
    {
        return DB::table('transactions')
            ->where('transaction_type', 'Sales')
            ->where('organization_id', $organizationId)
            ->whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->sum('total_amount');
    }

    /**
     * Get total tax paid
     */
    private function getTotalTaxPaid($organizationId, $currentYear, $currentMonth)
    {
        return DB::table('transactions')
            ->where('Paidstatus', 'Paid')
            ->whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->where('organization_id', $organizationId)
            ->sum('vat_amount');
    }

    /**
     * Get total purchases made
     */
    private function getTotalPurchasesMade($organizationId, $currentYear, $currentMonth)
    {
        return DB::table('transactions')
            ->where('transaction_type', 'Purchase')
            ->whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->where('organization_id', $organizationId)
            ->count();
    }

    /**
     * Get total cost of purchases
     */
    private function getTotalCostOfPurchases($organizationId, $currentYear, $currentMonth)
    {
        return DB::table('transactions')
            ->where('transaction_type', 'Purchase')
            ->whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->where('organization_id', $organizationId)
            ->sum('total_amount');
    }

    /**
     * Get tax return data
     */
    private function getTaxReturnData($organizationId, $taxReturnType)
    {
        return DB::table($taxReturnType)
            ->join('tax_returns', 'tax_returns.id', '=', $taxReturnType.'.tax_return_id')
            ->where('tax_returns.organization_id', $organizationId)
            ->whereNull('tax_returns.deleted_at')
            ->select(
                'tax_returns.year',
                'tax_returns.month',
                $taxReturnType.'.total_amount_payable',
                'tax_returns.id as tax_return_id',
                'tax_returns.status'
            )
            ->orderBy('tax_returns.year')
            ->orderBy('tax_returns.month')
            ->get()
            ->map(function ($item) {
                $quarter = ceil($item->month / 3);
                return [
                    'year' => $item->year,
                    'quarter' => $quarter,
                    'total_amount_payable' => $item->total_amount_payable,
                    'tax_return_id' => $item->tax_return_id,
                    'status' => $item->status
                ];
            });
    }

    /**
     * Get chart data for tax distribution
     */
    private function getChartData($organizationId, $currentYear)
    {
        $taxCounts = DB::table('transactions')
            ->join('tax_rows', 'transactions.id', '=', 'tax_rows.transaction_id')
            ->join('tax_types', 'tax_rows.tax_type', '=', 'tax_types.id')
            ->select(
                DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m") as month_year'), 
                'tax_types.tax_type',
                DB::raw('COUNT(*) as count')
            )
            ->where('tax_types.transaction_type', 'Sales')
            ->where('organization_id', $organizationId)
            ->where('transactions.created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy(DB::raw('month_year'), 'tax_types.tax_type')
            ->orderBy('month_year')
            ->get();

        $chartData = [];
        foreach ($taxCounts as $row) {
            $monthIndex = (int)date('n', strtotime($row->month_year)) - 1;
            $rowYear = (int)date('Y', strtotime($row->month_year));
            
            if ($rowYear !== $currentYear) {
                continue;
            }

            if (!isset($chartData[$row->tax_type])) {
                $chartData[$row->tax_type] = [
                    'label' => $row->tax_type,
                    'data' => array_fill(0, 12, 0),
                    'borderColor' => 'rgba(108, 159, 198, 1)',
                    'backgroundColor' => 'rgba(39, 86, 7, 0.2)',
                ];
            }
            
            $chartData[$row->tax_type]['data'][$monthIndex] += $row->count;
        }

        return $chartData;
    }

    /**
     * Get month labels
     */
    private function getMonthLabels()
    {
        return ["Jan", "Feb", "Mar", "Apr", "May", "Jun", 
                "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    }

    /**
     * Get bar chart data (monthly revenue distribution)
     */
    private function getBarChartData($organizationId)
    {
        $monthlyRevenue = DB::table('transactions')
            ->selectRaw('MONTH(date) as month, YEAR(date) as year, SUM(total_amount) as monthly_revenue')
            ->where('transaction_type', 'Sales')
            ->where('organization_id', $organizationId)
            ->where('date', '>=', Carbon::now()->subMonths(4))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        $barData = array_fill(0, 4, 0);
        $currentMonth = Carbon::now();
        $recentMonths = [];

        for ($i = 0; $i < 4; $i++) {
            $recentMonths[] = $currentMonth->copy()->subMonths($i)->format('F Y');
        }

        foreach ($monthlyRevenue as $revenue) {
            $monthYear = Carbon::createFromDate($revenue->year, $revenue->month, 1)->format('F Y');
            $monthIndex = array_search($monthYear, $recentMonths);
            
            if ($monthIndex !== false) {
                $barData[$monthIndex] = $revenue->monthly_revenue;
            }
        }

        return $barData;
    }

    /**
     * Get bar chart labels
     */
    private function getBarChartLabels()
    {
        $currentMonth = Carbon::now();
        $labels = [];
        
        for ($i = 3; $i >= 0; $i--) {
            $labels[] = $currentMonth->copy()->subMonths($i)->format('F Y');
        }

        return $labels;
    }

    /**
     * Get donut chart labels
     */
    private function getDonutChartLabels($organizationId)
    {
        return DB::table('transactions')
            ->join('tax_rows', 'transactions.id', '=', 'tax_rows.transaction_id')
            ->join('tax_types', 'tax_rows.tax_type', '=', 'tax_types.id')
            ->select('tax_types.tax_type as label')
            ->where('tax_types.transaction_type', 'Purchase')
            ->where('organization_id', $organizationId)
            ->where('transactions.date', '>=', Carbon::now()->subMonths(12))
            ->groupBy('tax_types.tax_type')
            ->orderBy(DB::raw('COUNT(*)'), 'desc')
            ->pluck('label')
            ->toArray();
    }

    /**
     * Get donut chart data
     */
    private function getDonutChartData($organizationId)
    {
        return DB::table('transactions')
            ->join('tax_rows', 'transactions.id', '=', 'tax_rows.transaction_id')
            ->join('tax_types', 'tax_rows.tax_type', '=', 'tax_types.id')
            ->select(DB::raw('COUNT(*) as count'))
            ->where('tax_types.transaction_type', 'Purchase')
            ->where('organization_id', $organizationId)
            ->where('transactions.date', '>=', Carbon::now()->subMonths(12))
            ->groupBy('tax_types.tax_type')
            ->orderBy('count', 'desc')
            ->pluck('count')
            ->toArray();
    }
}