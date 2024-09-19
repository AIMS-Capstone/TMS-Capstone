<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrgSetup;
use App\Models\Transactions;
use App\Models\Sales;

class DashboardController extends Controller
{
    public function show(Request $request, $orgId)
    {
        // Fetch organization by ID
        $organization = OrgSetup::find($orgId);

        if (!$organization) {
            return redirect()->route('org-setup')->with('error', 'Organization not found.');
        }

        // Load organization-specific data
        return view('dashboard', [
            'organization' => $organization,
            // 'transactions' => Transactions::where('org-setups_id', $orgId)->get(),
        ]); 
    }
}

