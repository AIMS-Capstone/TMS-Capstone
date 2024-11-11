<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use Illuminate\Http\Request;

class TransactionsRecycleBinController extends Controller
{
    /**
     * Display a listing of soft-deleted transactions.
     */
    public function index(Request $request)
    {
        // Start the query with only soft-deleted transactions, including deletedByUser relation
        $query = Transactions::onlyTrashed()->with(['deletedByUser', 'contactDetails', 'Organization']);

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

        // Paginate the filtered results
        $trashedTransactions = $query->paginate(5);

        // Return the view with the filtered and paginated trashed transactions
        return view('recycle-bin.transactions', compact('trashedTransactions'));
    }


    /**
     * Restore a soft-deleted transaction.
     */
   public function bulkRestore(Request $request)
    {
        $ids = $request->input('ids'); // Array of transaction IDs to restore
        Transactions::onlyTrashed()->whereIn('id', $ids)->restore();

        return response()->json(['success' => 'Selected transactions restored successfully.']);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids'); // Array of transaction IDs to permanently delete
        Transactions::onlyTrashed()->whereIn('id', $ids)->forceDelete();

        return response()->json(['success' => 'Selected transactions permanently deleted.']);
    }

}
