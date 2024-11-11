<?php

namespace App\Http\Controllers;

use App\Models\OrgSetup;
use Illuminate\Http\Request;

class OrganizationRecycleBinController extends Controller
{
    public function index(Request $request)
    {
        $query = OrgSetup::onlyTrashed()->with('deletedByUser');

        // Search functionality
        if ($request->has('user_search') && $request->user_search != '') {
            $search = $request->user_search;
            $query->where(function ($q) use ($search) {
                $q->where('registration_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('tax_type', 'LIKE', '%' . $search . '%')
                    ->orWhere('type', 'LIKE', '%' . $search . '%'); // Add other fields as needed
            });
        }

        $trashedOrganizations = $query->paginate(10);

        return view('recycle-bin.organization', compact('trashedOrganizations'));
    }

    public function bulkRestore(Request $request)
    {
        $ids = $request->input('ids');
        OrgSetup::onlyTrashed()->whereIn('id', $ids)->restore();

        return response()->json(['success' => 'Selected organizations restored successfully.']);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        OrgSetup::onlyTrashed()->whereIn('id', $ids)->forceDelete();

        return response()->json(['success' => 'Selected organizations permanently deleted.']);
    }
}
