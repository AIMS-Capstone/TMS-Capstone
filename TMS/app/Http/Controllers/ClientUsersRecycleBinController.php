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
