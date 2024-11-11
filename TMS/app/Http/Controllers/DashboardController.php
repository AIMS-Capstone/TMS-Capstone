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
        // Retrieve organization ID from the session
        $organizationId = $request->session()->get('organization_id');

        // Retrieve organization details based on organization_id
        $organization = OrgSetup::find($organizationId);

        $filedTaxReturnCount = TaxReturn::where('status', 'Filed')
        ->where('organization_id', $organizationId)
        ->count();
        $unfiledTaxReturnCount = TaxReturn::where('status', operator: 'Unfiled')
        ->where('organization_id', $organizationId)
        ->count();
        $totalSalesTransaction = Transactions::where('transaction_type', 'Sales')
        ->where('organization_id', $organizationId)
        ->count();
        $totalPurchaseTransaction = Transactions::where('transaction_type', 'Purchase')
        ->where('organization_id', $organizationId)
        ->count();
            

    // Pass data to the view
    return view('dashboard', compact('organization', 'filedTaxReturnCount', 'totalSalesTransaction','unfiledTaxReturnCount','totalPurchaseTransaction'));
    }
}

