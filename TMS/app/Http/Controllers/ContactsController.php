<?php

namespace App\Http\Controllers;

use App\Models\Contacts;
use App\Models\OrgAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str;

class ContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('contact_type');
        $perPage = $request->input('perPage', 5);

        $organizationId = session('organization_id');

        $query = Contacts::query();

        if ($organizationId) {
            $query->where('organization_id', $organizationId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('bus_name', 'like', "%{$search}%")
                    ->orWhere('contact_tin', 'like', "%{$search}%")
                    ->orWhere('contact_city', 'like', "%{$search}%");
            });
        }

        if ($type && $type !== 'All') {
            $query->where('contact_type', $type);
        }

        $contacts = $query->paginate($perPage);

        return view('contacts', compact('contacts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $organizationId = session('organization_id');

    // Check if the organization ID is available
    if (!$organizationId) {
        return redirect()->back()->withErrors(['error' => 'User does not have an organization ID.']);
    }

    // Validate the incoming request
    $validated = $request->validate([
        'contact_type' => 'required|string',
        'bus_name' => 'required|string|max:255', 
        'contact_email' => 'nullable|email|max:255', 
        'contact_phone' => 'nullable|string|max:20',
        'contact_tin' => 'nullable|string|max:255',
        'revenue_tax_type' => 'nullable|string|max:255', 
        'revenue_atc' => 'nullable|string|max:255',
        'revenue_chart_accounts' => 'nullable|string|max:255',
        'expense_tax_type' => 'nullable|string|max:255',
        'expense_atc' => 'nullable|string|max:255',
        'expense_chart_accounts' => 'nullable|string|max:255',
        'contact_address' => 'nullable|string|max:255',
        'contact_city' => 'required|string|max:255', 
        'contact_zip' => 'required|string|max:20',
    ]);

    try {
        
        $validated['organization_id'] = $organizationId;

        // Create the contact
        Contacts::create($validated);

        // Redirect with success message
        return redirect()->route('contacts.index')->with('success', 'Contact created successfully.');
    } catch (\Exception $e) {
        
        // Redirect back with an error message
        return redirect()->back()->withErrors(['error' => 'Failed to create contact. Please try again.']);
    }
}

    public function update(Request $request, $id)
    {
        // Validate the request data
        $validated = $request->validate([
            'contact_type' => 'required|string',
            'bus_name' => 'required|string|max:255',
            'contact_tin' => 'nullable|string|max:255',
            'contact_address' => 'nullable|string|max:255',
            'contact_city' => 'required|string|max:255',
            'contact_zip' => 'required|string|max:20',
        ]);

        try {
            // Find the contact by ID
            $contact = Contacts::findOrFail($id);

            // Update the contact with validated data
            $contact->update($validated);

            // Redirect with a success message
            return redirect()->route('contacts.index')->with('success', 'Contact updated successfully.');
        } catch (\Exception $e) {
            // Redirect back with an error message
            return redirect()->back()->withErrors(['error' => 'Failed to update contact: ' . $e->getMessage()]);
        }
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:contacts,id',
        ]); 

        // Soft delete the selected contacts
        Contacts::whereIn('id', $request->ids)->each(function ($contact) {
            $contact->delete(); // Trigger the `deleting` model event
        });

        return response()->json(['success' => true, 'message' => 'Contacts deleted successfully.']);
    }
}
