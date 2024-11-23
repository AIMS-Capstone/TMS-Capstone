<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeesRecycleBinController extends Controller
{
    public function index(Request $request)
    {
        
        $query = Employee::onlyTrashed()->with(['address', 'organization', 'deletedByUser']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%$search%")
                    ->orWhere('last_name', 'LIKE', "%$search%")
                    ->orWhere('tin', 'LIKE', "%$search%");
            });
        }

        // Paginate results
        $trashedEmployees = $query->paginate(5);

        return view('recycle-bin.employees', compact('trashedEmployees'));
    }

    public function bulkRestore(Request $request)
    {
        $ids = $request->input('ids'); // Array of employee IDs to restore
        Employee::onlyTrashed()->whereIn('id', $ids)->restore();

        return response()->json(['success' => 'Selected employees restored successfully.']);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids'); // Array of employee IDs to permanently delete
        Employee::onlyTrashed()->whereIn('id', $ids)->forceDelete();

        return response()->json(['success' => 'Selected employees permanently deleted.']);
    }
}
