<?php

namespace App\Http\Controllers;

use App\Models\Contacts;
use Illuminate\Http\Request;

class ContactsRecycleBinController extends Controller
{
    /**
     * Display a listing of soft-deleted contacts.
     */
    public function index(Request $request)
    {
        // Start the query with only soft-deleted contacts, including deletedByUser and organization relations
        $query = Contacts::onlyTrashed()->with(['deletedByUser', 'transactions', 'Organization']);
        $perPage = $request->input('perPage', 5);

        // Check if the search input is provided
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;

            // Add conditions to search in relevant fields
            $query->where(function ($q) use ($search) {
                $q->where('bus_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('contact_tin', 'LIKE', '%' . $search . '%')
                    ->orWhere('contact_type', 'LIKE', '%' . $search . '%')
                    ->orWhere('contact_address', 'LIKE', '%' . $search . '%');
            });
        }

        // Paginate the filtered results
        $trashedContacts = $query->paginate($perPage);

        // Return the view with the filtered and paginated trashed contacts
        return view('recycle-bin.contacts', compact('trashedContacts'));
    }

    /**
     * Restore a soft-deleted contact.
     */
    public function bulkRestore(Request $request)
    {
        $ids = $request->input('ids'); // Array of contact IDs to restore
        Contacts::onlyTrashed()->whereIn('id', $ids)->restore();

        return response()->json(['success' => 'Selected contacts restored successfully.']);
    }

    /**
     * Permanently delete a soft-deleted contact.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids'); // Array of contact IDs to permanently delete
        Contacts::onlyTrashed()->whereIn('id', $ids)->forceDelete();

        return response()->json(['success' => 'Selected contacts permanently deleted.']);
    }
}
