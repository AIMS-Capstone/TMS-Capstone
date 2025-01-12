<?php

namespace App\Http\Controllers;

use App\Exports\account_type_template;
use App\Exports\import_template;
use App\Imports\CoaImport;
use App\Models\Coa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel as MaatExcel;
use App\Exports\CoaExport;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class CoaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');
        $perPage = $request->input('perPage', 5);

        $organizationId = session('organization_id'); // Using session-based organization ID

        $query = Coa::where('organization_id', $organizationId)
            ->where('status', 'Active');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('type', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('sub_type', 'like', "%{$search}%");
            });
        }

        if ($type && $type !== 'All') {
            $query->where('type', $type);
        }

        $coas = $query->paginate($perPage);

        return view('coa', compact('coas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $organizationId = session('organization_id');

        if (!$organizationId) {
            return redirect()->back()->withErrors(['error' => 'User does not have an organization ID.']);
        }

        $submitAction = $request->input('submit_action');

        if ($submitAction === 'manual') {
            $request->validate([
                'type' => 'required|string|max:255',
                'sub_type' => 'required|string|max:255',
                'code' => 'required|string|max:10',
                'name' => 'required|string|max:150',
                'description' => 'nullable|string|max:255',
            ]);

            $coa = Coa::create([
                'type' => $request->input('type'),
                'sub_type' => $request->input('sub_type'),
                'code' => $request->input('code'),
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'organization_id' => $organizationId,
            ]);

            // Log COA creation
            activity('Charts of Accounts')
                ->performedOn($coa)
                ->causedBy(Auth::user())
                ->withProperties([
                    'organization_id' => $organizationId,
                    'coa_name' => $coa->name,
                    'type' => $coa->type,
                    'code' => $coa->code,
                    'ip' => request()->ip(),
                    'browser' => request()->header('User-Agent'),
                ])
                ->log("COA {$coa->name} was created.");

            return redirect()->route('coa')->with('success', 'Account created successfully.');
        } elseif ($submitAction === 'import') {
            // Import logic (CSV handling)
            return redirect()->route('coa')->with('success', 'CSV imported successfully.');
        }

        return redirect()->route('coa')->with('error', 'Invalid action.');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $organizationId = session('organization_id');

        if (!$organizationId) {
            return redirect()->back()->withErrors(['error' => 'User does not have an organization ID.']);
        }

        $request->validate([
            'account_type_input' => 'required|string|max:255',
            'sub_type' => 'required|string|max:255',
            'code' => 'required|string|max:10',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:255',
        ]);

        // Retrieve COA and original attributes
        $coa = Coa::where('id', $id)
            ->where('organization_id', $organizationId)
            ->firstOrFail();

        $oldAttributes = $coa->getOriginal();

        // Perform update
        $coa->update([
            'type' => $request->input('account_type_input'),
            'sub_type' => $request->input('sub_type'),
            'code' => $request->input('code'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        // Get updated attributes and detect changes
        $changedAttributes = $coa->getChanges();
        $changes = [];

        foreach ($changedAttributes as $key => $newValue) {
            $oldValue = $oldAttributes[$key] ?? 'N/A';
            $changes[$key] = [
                'old' => $oldValue,
                'new' => $newValue,
            ];
        }

        // Log the changes
        activity('Charts of Accounts')
            ->performedOn($coa)
            ->causedBy(Auth::user())
            ->withProperties([
                'organization_id' => $coa->organization_id,
                'changes' => $changes,
                'ip' => request()->ip(),
                'browser' => request()->header('User-Agent'),
            ])
            ->log("COA {$coa->name} was updated.");

        return redirect()->route('coa')->with('success', 'Account updated successfully.');
    }

    public function deactivate(Request $request)
    {
        $organizationId = session('organization_id');

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:coas,id',
        ]);

        $coas = Coa::whereIn('id', $request->ids)
            ->where('organization_id', $organizationId)
            ->get();

        foreach ($coas as $coa) {
            $oldStatus = $coa->status;

            // Deactivate COA
            $coa->update(['status' => 'Inactive']);

            activity('Charts of Accounts')
                ->performedOn($coa)
                ->causedBy(Auth::user())
                ->withProperties([
                    'organization_id' => $coa->organization_id,
                    'old_status' => $oldStatus,
                    'new_status' => 'Inactive',
                    'ip' => request()->ip(),
                    'browser' => request()->header('User-Agent'),
                ])
                ->log("COA {$coa->name} was deactivated.");
        }

        return response()->json(['message' => 'Selected CoAs have been deactivated.']);
    }

    public function destroy(Request $request)
    {
        $organizationId = session('organization_id');

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:coas,id',
        ]);

        $coas = Coa::whereIn('id', $request->ids)
            ->where('status', 'Inactive')
            ->where('organization_id', $organizationId)
            ->get();

        foreach ($coas as $coa) {
            $coa->delete();

            activity('Charts of Accounts')
                ->performedOn($coa)
                ->causedBy(Auth::user())
                ->withProperties([
                    'organization_id' => $coa->organization_id,
                    'ip' => request()->ip(),
                    'browser' => request()->header('User-Agent'),
                ])
                ->log("COA {$coa->name} was deleted.");
        }

        return response()->json(['message' => 'Selected archived COAs have been deleted successfully.'], 200);
    }

    public function archive(Request $request)
    {
        $organizationId = session('organization_id');
        $search = $request->input('search');
        $type = $request->input('type');
        $perPage = $request->input('perPage', 5);

        $query = Coa::where('status', 'Inactive')
            ->where('organization_id', $organizationId);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('type', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($type && $type !== 'All') {
            $query->where('type', $type);
        }

        $inactiveCoas = $query->paginate($perPage);

        return view('components.coa-archive', compact('inactiveCoas'));
    }

    public function restore(Request $request)
    {
        $organizationId = session('organization_id');

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:coas,id',
        ]);

        $coas = Coa::whereIn('id', $request->ids)
            ->where('organization_id', $organizationId)
            ->get();

        foreach ($coas as $coa) {
            $oldStatus = $coa->status;

            // Restore COA
            $coa->update(['status' => 'Active']);

            activity('Charts of Accounts')
                ->performedOn($coa)
                ->causedBy(Auth::user())
                ->withProperties([
                    'organization_id' => $coa->organization_id,
                    'old_status' => $oldStatus,
                    'new_status' => 'Active',
                    'ip' => request()->ip(),
                    'browser' => request()->header('User-Agent'),
                ])
                ->log("COA {$coa->name} was restored.");
        }

        return response()->json(['message' => 'Selected COAs have been restored successfully.'], 200);
    }

    public function download_coa(Request $request)
    {
        $organizationId = session('organization_id');

        $coas = Coa::where('status', 'Active')
            ->where('organization_id', $organizationId)
            ->get();

        $data = [
            'title' => 'Available Charts of Accounts',
            'date' => date('m/d/Y'),
            'coas' => $coas,
        ];

        $pdf = PDF::loadView('coaPDF', $data);

        return $pdf->download('Coa.pdf');
    }

    public function import_template()
    {
        return MaatExcel::download(new import_template, 'import_template.xlsx');
    }

    public function account_type_template()
    {
        return MaatExcel::download(new account_type_template, 'account_type_template.xlsx');
    }

    public function exportCoas()
    {
        return MaatExcel::download(new CoaExport, "coa_list.csv");
    }

    public function import(Request $request)
    {
        // Validate the file input
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        // Import the file
        MaatExcel::import(new CoaImport, $request->file('file'));

        return back()->with('success', 'COA data imported successfully!');
    }


}
