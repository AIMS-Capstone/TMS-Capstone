<?php

namespace App\Http\Controllers;

use App\Exports\AtcExport;
use App\Exports\TaxTypeExport;
use App\Models\TaxType;
use App\Http\Requests\StoreTaxTypeRequest;
use App\Http\Requests\UpdateTaxTypeRequest;
use Maatwebsite\Excel\Facades\Excel;

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
    public function exportTaxType($type)
    {
        // Validate that the type is either 'purchase' or 'sale'
        if (!in_array($type, ['purchase', 'sales'])) {
            return redirect()->back()->with('error', 'Invalid type.');
        }

        // Pass the type to the export class and trigger the download
        return Excel::download(new TaxTypeExport($type), "{$type}_tax_types.csv");
    }
  
}
