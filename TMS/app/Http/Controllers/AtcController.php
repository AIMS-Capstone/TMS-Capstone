<?php

namespace App\Http\Controllers;

use App\Exports\AtcExport;
use App\Models\Atc;
use App\Http\Requests\StoreatcRequest;
use App\Http\Requests\UpdateatcRequest;
use Maatwebsite\Excel\Facades\Excel;

class AtcController extends Controller
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
    public function store(StoreatcRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(atc $atc)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(atc $atc)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateatcRequest $request, atc $atc)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(atc $atc)
    {
        //
    }

    public function exportAtcs($type)
    {
        // Validate that the type is either 'purchase' or 'sale'
        if (!in_array($type, ['purchase', 'sales'])) {
            return redirect()->back()->with('error', 'Invalid type.');
        }

        // Pass the type to the export class and trigger the download
        return Excel::download(new AtcExport($type), "{$type}_atcs.csv");
    }
}
