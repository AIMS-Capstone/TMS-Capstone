<?php

namespace App\Http\Controllers;

use App\Models\OrgSetup;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

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

        return view('org-setup', compact('orgsetups'));
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
        // Validate the request data
        $validatedData = $request->validate([
            'type' => 'required|in:non-individual,individual',
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
            'registration_date' => 'required|date_format:m/d/Y',
            'start_date' => 'required|date_format:m/d/Y',
            'financial_year_end' => 'required|string|max:255',
        ]);


        // pang bago nung format sa db para tumangan ng date
        $validatedData['start_date'] = Carbon::createFromFormat('m/d/Y', $validatedData['start_date'])->format('Y-m-d');
        $validatedData['registration_date'] = Carbon::createFromFormat('m/d/Y', $validatedData['registration_date'])->format('Y-m-d');
      


        // Create new OrgSetup record
        OrgSetup::create($validatedData);

        // Redirect to the org-setup route with a success message
        return redirect()->route('org-setup')->with('success', 'Organization setup created successfully.');
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
    public function destroy(OrgSetup $orgSetup)
    {
        //
    }
}
