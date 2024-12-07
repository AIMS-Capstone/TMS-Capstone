<?php

namespace App\Http\Controllers;

use App\Models\TaxReturn;
use Illuminate\Http\Request;

class TaxReturnsRecycleBinController extends Controller
{
    /**
     * Display a listing of soft-deleted tax returns.
     */
    public function index(Request $request)
    {
        $query = TaxReturn::onlyTrashed()->with(['deletedByUser', 'Organization', 'user']);
        $perPage = $request->input('perPage', 5);

        // Check if the search input is provided
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;

            // Add conditions to search in relevant fields
            $query->where(function ($q) use ($search) {
                $q->whereHas('contactDetails', function ($q) use ($search) {
                    $q->where('bus_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('contact_address', 'LIKE', '%' . $search . '%')
                        ->orWhere('contact_tin', 'LIKE', '%' . $search . '%');
                })
                    ->orWhere('inv_number', 'LIKE', '%' . $search . '%')
                    ->orWhere('reference', 'LIKE', '%' . $search . '%')
                    ->orWhere('transaction_type', 'LIKE', '%' . $search . '%');
            });
        }

        $trashedTaxReturn = TaxReturn::onlyTrashed()->paginate($perPage);

        return view('recycle-bin.tax-returns', compact('trashedTaxReturn'));
    }

    /**
     * Restore selected soft-deleted tax returns.
     */
    public function bulkRestore(Request $request)
    {
        $ids = $request->input('ids'); // Array of tax return IDs to restore
        TaxReturn::onlyTrashed()->whereIn('id', $ids)->restore();

        return response()->json(['success' => 'Selected tax returns restored successfully.']);
    }

    /**
     * Permanently delete selected soft-deleted tax returns.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids'); // Array of tax return IDs to permanently delete
        TaxReturn::onlyTrashed()->whereIn('id', $ids)->forceDelete();

        return response()->json(['success' => 'Selected tax returns permanently deleted.']);
    }
}
