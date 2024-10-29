<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatecoaRequest;
use App\Imports\CoaImport;
use App\Models\coa;
// use Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel as MaatExcel;
use App\Exports\ExportCoa;
use App\Exports\import_template;
use App\Exports\account_type_template;

class CoaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');

        $query = Coa::where('status', 'Active');

        // Apply search if there is one
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('type', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('sub_type', 'like', "%{$search}%"); // Include sub_type in search
            });
        }

        // Apply type filter if specified and not 'All'
        if ($type && $type !== 'All') {
            $query->where('type', $type);
        }

        // Paginate the results
        $coas = $query->paginate(5);

        return view('coa', compact('coas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Determine which action is being performed (manual add or CSV import)
        $submitAction = $request->input('submit_action');

        if ($submitAction === 'manual') {
            // Handle manual creation
            $request->validate([
                'account_type_input' => 'required|string|max:255',
                'code' => 'required|string|max:10',
                'name' => 'required|string|max:150',
                'description' => 'nullable|string|max:255',
            ]);

            // Split the input into type and sub_type
            $accountTypeInput = $request->input('account_type_input');

            // Split the input and make sure to handle any potential whitespaces
            $parts = array_map('trim', explode('|', $accountTypeInput));

            // Check if we have both type and sub_type from the input
            $type = $parts[0] ?? null;
            $subType = $parts[1] ?? null;

            // Ensure both are not null and then proceed
            if ($type && $subType) {
                Coa::create([
                    'type' => $type,
                    'sub_type' => $subType, 
                    'code' => $request->input('code'),
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                ]);

return redirect()->route('coa')->with('success', 'Account created successfully.');

            } else {
                return redirect()->back()->withErrors(['account_type_input' => 'Please provide a valid input in the format: "Type | Sub Type".']);
            }

        } elseif ($submitAction === 'import') {

            MaatExcel::import(new CoaImport, $request->file('sample'));

            // Handle CSV import
            $request->validate([
                'csv_file' => 'required|file|mimes:csv|max:2048',
            ]);

            return redirect()->route('coa')->with('success', 'CSV imported successfully.');
        }

        // Fallback for unsupported actions
        return redirect()->route('coa')->with('error', 'Invalid action.');
    }

    /**
     * Display the specified resource.
     */
    public function show(coa $coa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(coa $coa)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'account_type_input' => 'required|string|max:255',
            'code' => 'required|string|max:10',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:255',
        ]);

// Split the input into type and sub_type
$accountTypeInput = $request->input('account_type_input');
$parts = array_map('trim', explode('|', $accountTypeInput));

// Check if we have both type and sub_type from the input
$type = $parts[0] ?? null;
$subType = $parts[1] ?? null;

// Update the COA entry
$coa = Coa::findOrFail($id);
$coa->update([
    'type' => $type,
    'sub_type' => $subType, // Ensure the column exists and is properly defined in the database
    'code' => $request->input('code'),
    'name' => $request->input('name'),
    'description' => $request->input('description'),
]);


return redirect()->route('coa')->with('success', 'Account updated successfully.');

    }

    public function deactivate(Request $request)
    {

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:coas,id',
        ]);

        Coa::whereIn('id', $request->ids)
            ->update(['status' => 'Inactive']);

        return response()->json(['message' => 'Selected CoAs have been deactivated.']);
    }

    public function destroy(Request $request)
    {

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:coas,id',
        ]);

        Coa::whereIn('id', $request->ids)
            ->where('status', 'Inactive')
            ->delete();

        return response()->json(['message' => 'Selected archived COAs have been deleted successfully.'], 200);
    }

    public function archive(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');

        $query = Coa::where('status', 'Inactive');

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
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:coas,id',
        ]);

        Coa::whereIn('id', $request->ids)->update(['status' => 'Active']);

        return response()->json(['message' => 'Selected COAs have been restored successfully.'], 200);
    }

    public function download_coa(Request $request)
    {
        // Get the active COAs
        $coas = Coa::where('status', 'Active')->get();

        // Prepare the data to pass to the view
        $data = [
            'title' => 'Available Charts of Accounts',
            'date' => date('m/d/Y'),
            'coas' => $coas, // Pass the COAs to the view
        ];

        // Load the view and pass the data
        $pdf = PDF::loadView('coaPDF', $data);

        // Download the PDF with a custom name
        return $pdf->download('Coa.pdf');
    }

    public function import_template(){
        return MaatExcel::download(new import_template, 'import_template.xlsx');
    }

    public function account_type_template(){
        return MaatExcel::download(new account_type_template, 'account_type_template.xlsx');
    }

}