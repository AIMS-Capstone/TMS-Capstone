<?php

namespace App\Http\Controllers;

use App\Models\OrgSetup;
use App\Models\TaxReturn;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrgSetupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 5); 
        
        $query = OrgSetup::query();
        
        if ($search) {  
            $query->where('registration_name', 'like', "%{$search}%")
                ->orWhere('tax_type', 'like', "%{$search}%")
                ->orWhere('type', 'like', "%{$search}%");
        }

        $orgsetups = $query->paginate($perPage);
        $unfiledTaxReturnsCount = TaxReturn::where('status', 'Unfiled')->count();
        $filedTaxReturnsCount = TaxReturn::where('status', 'Filed')->count();
        $orgSetupCount = OrgSetup::count();
        $nonIndividualClients = OrgSetup::where('type', 'Non-Individual')->count();
        $individualClients = OrgSetup::where('type', 'Individual')->count();


        return view('org-setup', compact('orgsetups', 'unfiledTaxReturnsCount', 'filedTaxReturnsCount', 'orgSetupCount', 'nonIndividualClients', 'individualClients'));
    }

    /**
     * Show the form for creating a new resource.
     */

     public function setOrganization(Request $request)
     {
         $organizationId = $request->input('organization_id');
     
         // Store the organization ID in the session
         session(['organization_id' => $organizationId]);
     
         // Redirect to the dashboard or some other page
         return redirect()->route('dashboard')->with('success', 'Organization selected successfully.');
     }
     
    public function create(Request $request)
    {

    }   

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Manually validate the request
            $validatedData = $request->validate([
                'type' => 'required|in:Non-Individual,Individual',
                'registration_name' => 'required|string|max:255',
                'line_of_business' => 'required|string|max:255',
                'address_line' => 'required|string|max:255',
                'region' => 'required|string|max:255',
                'province' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'zip_code' => 'required|string|max:10',
                'contact_number' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'tin' => 'required|string|max:20|unique:org_setups,tin',
                'rdo' => 'required|string|max:20',
                'tax_type' => 'required|string|max:255',
                'registration_date' => 'required|date',
                'start_date' => 'required|date',
                'financial_year_end' => 'required|string|max:255',
            ]);




            OrgSetup::create($validatedData);

            return redirect()->back()->with('success', 'Organization setup created successfully.');

        } catch (ValidationException $e) {
            // Handle validation errors here
            $errors = $e->errors();
            $requestData = request()->all();

            // Dump or log errors to examine them (or handle them as needed)
            dd([
                'errors' => $errors,
                'input_data' => $requestData,
            ]);

            // Redirect back with errors if needed
            return redirect()->back()->withErrors($errors)->withInput();
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(OrgSetup $orgSetup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrgSetup $orgSetup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrgSetup $orgSetup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'organization_id' => 'required|exists:org_setups,id',
        ]);
    
        $organization = OrgSetup::findOrFail($request->organization_id);
        $organization->delete(); // Perform the deletion
    
        return redirect()->back()->with('success', 'Organization deleted successfully.');
    }
}
