<?php

namespace App\Http\Controllers;

use App\Models\OrgAccount;
use Illuminate\Http\Request;

class ClientUsersRecycleBinController extends Controller
{
    public function index(Request $request)
    {
        $query = OrgAccount::onlyTrashed()->with(['deletedByUser']);
        $perPage = $request->input('perPage', 5);

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;

            $query->whereHas('orgSetup', function ($orgQuery) use ($search) {
                $orgQuery->where('registration_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('tin', 'LIKE', '%' . $search . '%');
            });
        }

        $trashedClientUsers = $query->paginate($perPage);

        return view('recycle-bin.client-users', compact('trashedClientUsers'));
    }

    public function bulkRestore(Request $request)
    {
        $ids = $request->input('ids');
        OrgAccount::onlyTrashed()->whereIn('id', $ids)->restore();

        return response()->json(['success' => 'Selected client users restored successfully.']);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        OrgAccount::onlyTrashed()->whereIn('id', $ids)->forceDelete();

        return response()->json(['success' => 'Selected client users permanently deleted.']);
    }
}
