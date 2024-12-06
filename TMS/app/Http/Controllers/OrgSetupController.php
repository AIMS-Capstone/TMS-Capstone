<?php

namespace App\Http\Controllers;

use App\Models\OrgSetup;
use App\Models\Rdo;
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
        $organizations = OrgSetup::all(); // Fetch all organizations
        $rdos = Rdo::all(); // Fetch all RDOs
        $regions = json_decode(file_get_contents(public_path('json/regions.json')), true);
        $provinces = json_decode(file_get_contents(public_path('json/provinces.json')), true);
        $municipalities = json_decode(file_get_contents(public_path('json/municipalities.json')), true);


        return view('org-setup', compact('orgsetups', 'unfiledTaxReturnsCount', 'filedTaxReturnsCount', 'orgSetupCount', 'nonIndividualClients', 'individualClients', 'rdos', 'regions', 'provinces', 'municipalities'));
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

        // Retrieve the start date input
     $startDate = $request->input('start_date');

    // If the start date is in YYYY-MM format (e.g., '2024-06'), append '-01' to make it a full date (YYYY-MM-DD)
    if (strlen($startDate) == 7) {  // Format like '2024-06'
        $startDate .= '-01';  // Append the first day of the month to make it a valid date
    }

    // Update the start_date input to ensure it has the correct format before validation
    $request->merge(['start_date' => $startDate]);  // Merges the corrected start_date into the request


    
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
                'start_date' => 'required|date',  // Now it's guaranteed to be in a full date format (YYYY-MM-DD)
                'financial_year_end' => 'required|string|max:5',  // Keep this for storing the financial year end as MM-DD
            ]);
    
         
            // Parse start date to get the year and month (Now it's in YYYY-MM-DD format)
            $startDate = Carbon::parse($startDate);
           
        
            // Calculate the financial year end (1 year later)
            $financialYearEnd = $startDate->addYear()->endOfMonth();  // Default to the last day of the month
        
            // If the start date is February, manually set it to February 28 (or 29 for leap years)
            if ($startDate->month == 2) {
                $financialYearEnd = $financialYearEnd->month(2)->day(28); // Always set to February 28
            }
        
            // Store the financial year end in 'MM-DD' format as a string (e.g., '02-28')
            $validatedData['financial_year_end'] = $financialYearEnd->format('m-d');
        
            // Save the data in the OrgSetup table
            OrgSetup::create($validatedData);
        
            return redirect()->back()->with('success', 'Organization setup created successfully.');
        
        } catch (ValidationException $e) {
            // Handle validation errors
            $errors = $e->errors();
            $requestData = request()->all();
            
            // Log the errors and input data for debugging
            dd(['errors' => $errors, 'input_data' => $requestData]);
        
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
    public function update(Request $request, $id)
    {
        // Find the organization by ID
        $organization = OrgSetup::findOrFail($id);
    
        // Manually validate the request
        $validatedData = $request->validate([
            'address_line' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10',
            'rdo' => 'required|string|max:20',
            'contact_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);
    
        // Update the organization with validated data
        $organization->update($validatedData);
    
        // Redirect back with success message
        return redirect()->back()->with('success', 'Organization details updated successfully.');
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
        $organization->delete(); // Perform the soft delete

        return redirect()->back()->with('success', 'Organization deleted successfully and moved to Recycle Bin.');
    }
}
