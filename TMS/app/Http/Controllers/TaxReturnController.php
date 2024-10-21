<?php

namespace App\Http\Controllers;

use App\Models\TaxReturn;
use App\Http\Requests\StoreTaxReturnRequest;
use App\Http\Requests\UpdateTaxReturnRequest;

class TaxReturnController extends Controller
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaxReturnRequest $request)
    {
        // Create a new tax return record
      
        
        $taxReturn = TaxReturn::create([
            'title' => $request->type,
            'year' => $request->year,
            'month' => $request->month,
            'created_by' => auth()->id(),
            'organization_id' => $request->organization_id,
            'status' => 'Unfiled',
        ]);

        return redirect()->route('vat_return')->with('success', 'Tax Return created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TaxReturn $taxReturn)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaxReturn $taxReturn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaxReturnRequest $request, TaxReturn $taxReturn)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaxReturn $taxReturn)
    {
        //
    }
}
