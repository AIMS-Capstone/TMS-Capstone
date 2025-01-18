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
use Illuminate\Support\Facades\Auth;

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
        try {
            $validatedData = $request->validate([
                'type' => 'required|in:Non-Individual,Individual',
                'registration_name' => 'required|string|max:255',
                'line_of_business' => 'required|string|max:50',
                'address_line' => 'required|string|max:255',
                'region' => 'required|string|max:255',
                'province' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'zip_code' => 'required|string|max:10',
                'contact_number' => ['required', 'string', 'size:11', 'regex:/^09\d{9}$/'],
                'email' => 'required|email|max:255',
                'tin' => ['required', 'string', 'max:20', 'unique:org_setups,tin', 'regex:/^\d{3}-\d{3}-\d{3}(-\d{3}|-\d{5})?$/'],
                'rdo' => 'required|exists:rdos,id',
                'tax_type' => 'required|in:Percentage Tax,Value-Added Tax,Tax Exempt',
                'start_date' => 'required|date_format:Y-m',
                'registration_date' => 'required|date',
                'financial_year_end' => 'required|string|max:5'
            ]);
    
            // Append -01 to start_date to make it a full date
            $validatedData['start_date'] = $validatedData['start_date'] . '-01';
    
            // Create the organization
            $organization = OrgSetup::create($validatedData);

            // Log activity
            activity('Organization Management')
                ->performedOn($organization)
                ->causedBy(Auth::user())
                ->withProperties(array_merge($validatedData, [
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ]))
                ->log("Organization {$organization->registration_name} created.");

            return response()->json(['success' => true]);
    
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
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
        $organization = OrgSetup::findOrFail($id);

        $validatedData = $request->validate([
            'address_line' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10',
            'rdo' => 'required|string|max:20',
            'contact_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        $originalData = $organization->getOriginal();
        $organization->update($validatedData);

        // Log activity
        activity('Organization Management')
            ->performedOn($organization)
            ->causedBy(Auth::user())
            ->withProperties([
                'before' => $originalData,
                'after' => $validatedData,
                'ip' => $request->ip(),
                'browser' => $request->header('User-Agent'),
            ])
            ->log("Organization {$organization->registration_name} updated.");

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
        $organizationName = $organization->registration_name;
        $organization->delete();

        // Log activity
        activity('Organization Management')
            ->performedOn($organization)
            ->causedBy(Auth::user())
            ->withProperties([
                'organization_id' => $organization->id,
                'ip' => $request->ip(),
                'browser' => $request->header('User-Agent'),
            ])
            ->log("Organization {$organizationName} deleted.");

        return redirect()->back()->with('success', 'Organization deleted successfully and moved to Recycle Bin.');
    }
}
