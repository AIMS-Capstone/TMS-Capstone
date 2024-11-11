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

        // Search functionality
        if ($request->filled('user_search')) {
            $search = $request->input('user_search');
            $query->where(function ($q) use ($search) {
                $q->where('email', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('orgSetup', function ($q) use ($search) {
                        $q->where('registration_name', 'LIKE', '%' . $search . '%')
                            ->orWhere('tax_type', 'LIKE', '%' . $search . '%')
                            ->orWhere('type', 'LIKE', '%' . $search . '%'); // Add other fields as needed
                    });
            });
        }

        $trashedAccountantUsers = $query->paginate(10);

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
