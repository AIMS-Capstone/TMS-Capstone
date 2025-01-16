<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\OrgSetup;
use Illuminate\Http\Request;

class AccountantUsersRecycleBinController extends Controller
{
    public function index(Request $request)
    {
        $query = User::onlyTrashed()->with(['deletedByUser']);
        $perPage = $request->input('perPage', 5);

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%')
                    ->orWhere('role', 'LIKE', '%' . $search . '%');
            });
        }

        $trashedAccountantUsers = $query->paginate($perPage);

        return view('recycle-bin.accountant-users', compact('trashedAccountantUsers'));
    }

    public function bulkRestore(Request $request)
    {
        $ids = $request->input('ids');
        User::onlyTrashed()->whereIn('id',  $ids)->restore();

        return response()->json(['success' => 'Selected accountant user/s restored successfully.']);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        User::onlyTrashed()->whereIn('id', $ids)->forceDelete();

        return response()->json(['success' => 'Selected client users permanently deleted.']);
    }
}
