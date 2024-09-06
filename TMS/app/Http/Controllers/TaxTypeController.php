<?php

namespace App\Http\Controllers;

use App\Models\TaxType;
use App\Http\Requests\StoreTaxTypeRequest;
use App\Http\Requests\UpdateTaxTypeRequest;


class TaxTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      
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
    public function store(StoreTaxTypeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TaxType $taxType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaxType $taxType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaxTypeRequest $request, TaxType $taxType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaxType $taxType)
    {
        //
    }
  
}
