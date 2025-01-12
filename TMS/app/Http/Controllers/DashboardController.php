<?php

namespace App\Http\Controllers;

use App\Models\TaxReturn;
use Illuminate\Http\Request;
use App\Models\OrgSetup;
use App\Models\Transactions;
use App\Models\Sales;

class DashboardController extends Controller
{
    public function showDashboard(Request $request)
    {
        $organizationId = $request->session()->get('organization_id');

        // Retrieve organization details based on organization_id
        $organization = OrgSetup::find($organizationId);

        // Count for filed tax returns
        $filedTaxReturnCount = TaxReturn::where('status', 'Filed')
            ->where('organization_id', $organizationId)
            ->count();

        // Count for sales and purchase transactions
        $totalSalesTransaction = Transactions::where('transaction_type', 'Sales')
            ->where('organization_id', $organizationId)
            ->count();
        $totalPurchaseTransaction = Transactions::where('transaction_type', 'Purchase')
            ->where('organization_id', $organizationId)
            ->count();

        $todayTaxReturns = TaxReturn::where('organization_id', $organizationId)
            ->whereDate('created_at', today())
            ->get();
        $upcomingTaxReturns = TaxReturn::where('organization_id', $organizationId)
            ->whereDate('created_at', '>', today())
            ->get();
        $completedTaxReturns = TaxReturn::where('organization_id', $organizationId)
            ->where('status', 'Completed')
            ->where('organization_id', $organizationId)
            ->get();
        $pendingTaxReturnCount = TaxReturn::where('organization_id', $organizationId)
            ->whereNotIn('status', ['Filed', 'Completed'])
            ->count();


        // Pass the filtered tax returns to the view
        return view('dashboard', compact(
            'organization', 
            'filedTaxReturnCount', 
            'totalSalesTransaction', 
            'totalPurchaseTransaction',
            'todayTaxReturns', 
            'upcomingTaxReturns', 
            'completedTaxReturns',
            'pendingTaxReturnCount'
        ));        
}
}