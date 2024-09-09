<?php

namespace App\Http\Controllers;

use App\Models\OrgSetup;
use Illuminate\Http\Request;

class OrgSetupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create(Request $request)
    {
        return view('organization.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        // $request->validate([
        //     'type' => 'required|in:non-individual,individual',
        //     'registration_name' => 'required|string|max:255',
        //     'line_of_business' => 'required|string|max:255',
        //     'address_line' => 'required|string|max:255',
        //     'address' => 'required|string|max:255',
        //     'contact_number' => 'required|string|max:20',
        //     'email' => 'required|email|max:255',
        //     'tin' => 'required|string|max:20',
        //     'rdo' => 'required|string|max:20',
        //     'tax_type' => 'required|string|max:255',
        //     'registration_date' => 'required|date',
        //     'start_date' => 'required|date',
        //     'financial_year_end' => 'required|date',
        // ]);

        // Create new OrgSetup record
        OrgSetup::create([
            'fname' => $request->input('fname'),
            'lname' => $request->input('lname'),
            'type' => $request->input('type'),
            'registration_name' => $request->input('registration_name'),
            'line_of_business' => $request->input('line_of_business'),
            'address_line' => $request->input('address_line'),
            'address' => $request->input('address'),
            'contact_number' => $request->input('contact_number'),
            'email' => $request->input('email'),
            'tin' => $request->input('tin'),
            'rdo' => $request->input('rdo'),
            'tax_type' => $request->input('tax_type'),
            'registration_date' => $request->input('registration_date'),
            'start_date' => $request->input('start_date'),
            'financial_year_end' => $request->input('financial_year_end'),
        ]);

        // Return a response (customize as needed)
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
