<?php

namespace App\Http\Controllers;

use App\Exports\account_type_template;
use App\Exports\import_template;
use App\Imports\CoaImport;
use App\Models\Coa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel as MaatExcel;

class CoaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');

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

        $coas = $query->paginate(5);

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
                'account_type_input' => 'required|string|max:255',
                'code' => 'required|string|max:10',
                'name' => 'required|string|max:150',
                'description' => 'nullable|string|max:255',
            ]);

            $accountTypeInput = $request->input('account_type_input');
            $parts = array_map('trim', explode('|', $accountTypeInput));
            $type = $parts[0] ?? null;
            $subType = $parts[1] ?? null;

            if ($type && $subType) {
                Coa::create([
                    'type' => $type,
                    'sub_type' => $subType,
                    'code' => $request->input('code'),
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                    'organization_id' => $organizationId,
                ]);

                return redirect()->route('coa')->with('success', 'Account created successfully.');
            } else {
                return redirect()->back()->withErrors(['account_type_input' => 'Please provide a valid input in the format: "Type | Sub Type".']);
            }
        } elseif ($submitAction === 'import') {
            $request->validate([
                'csv_file' => 'required|file|mimes:csv,xlsx|max:2048',
            ]);

            dd("Starting import with organization_id: {$organizationId}");

            // Pass organization ID when creating CoaImport instance
            MaatExcel::import(new CoaImport($organizationId), $request->file('csv_file'));

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

        $request->validate([
            'account_type_input' => 'required|string|max:255',
            'code' => 'required|string|max:10',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:255',
        ]);

        $accountTypeInput = $request->input('account_type_input');
        $parts = array_map('trim', explode('|', $accountTypeInput));
        $type = $parts[0] ?? null;
        $subType = $parts[1] ?? null;

        $coa = Coa::where('id', $id)
            ->where('organization_id', $organizationId)
            ->firstOrFail();

        $coa->update([
            'type' => $type,
            'sub_type' => $subType,
            'code' => $request->input('code'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('coa')->with('success', 'Account updated successfully.');
    }

    public function deactivate(Request $request)
    {
        $organizationId = session('organization_id');

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:coas,id',
        ]);

        Coa::whereIn('id', $request->ids)
            ->where('organization_id', $organizationId)
            ->update(['status' => 'Inactive']);

        return response()->json(['message' => 'Selected CoAs have been deactivated.']);
    }

    public function destroy(Request $request)
    {
        $organizationId = session('organization_id');

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:coas,id',
        ]);

        Coa::whereIn('id', $request->ids)
            ->where('status', 'Inactive')
            ->where('organization_id', $organizationId)
            ->delete();

        return response()->json(['message' => 'Selected archived COAs have been deleted successfully.'], 200);
    }

    public function archive(Request $request)
    {
        $organizationId = session('organization_id');
        $search = $request->input('search');
        $type = $request->input('type');

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

        $inactiveCoas = $query->paginate(5);

        return view('components.coa-archive', compact('inactiveCoas'));
    }

    public function restore(Request $request)
    {
        $organizationId = session('organization_id');

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:coas,id',
        ]);

        Coa::whereIn('id', $request->ids)
            ->where('organization_id', $organizationId)
            ->update(['status' => 'Active']);

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
}
