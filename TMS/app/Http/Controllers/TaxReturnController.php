<?php

namespace App\Http\Controllers;

use App\Models\TaxReturn;
use App\Http\Requests\StoreTaxReturnRequest;
use App\Http\Requests\UpdateTaxReturnRequest;
use App\Models\Transactions;
use Carbon\Carbon;
use PDF; 

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
        // Step 1: Create the tax return
        $taxReturn = TaxReturn::create([
            'title' => $request->type,
            'year' => $request->year,
            'month' => $request->month,
            'created_by' => auth()->id(),
            'organization_id' => $request->organization_id,
            'status' => 'Unfiled',
        ]);
    
        // Step 2: Determine the date range based on the selected month/quarter
        $startDate = null;
        $endDate = null;
    
        // If it's a month selection (1-12)
        if (is_numeric($request->month)) {
            $startDate = Carbon::create($request->year, $request->month, 1)->startOfMonth();
            $endDate = Carbon::create($request->year, $request->month, 1)->endOfMonth();
        } 
        // If it's a quarter (Q1, Q2, Q3, Q4)
        else if ($request->month == 'Q1') {
            $startDate = Carbon::create($request->year, 1, 1)->startOfMonth();
            $endDate = Carbon::create($request->year, 3, 31)->endOfMonth();
        } else if ($request->month == 'Q2') {
            $startDate = Carbon::create($request->year, 4, 1)->startOfMonth();
            $endDate = Carbon::create($request->year, 6, 30)->endOfMonth();
        } else if ($request->month == 'Q3') {
            $startDate = Carbon::create($request->year, 7, 1)->startOfMonth();
            $endDate = Carbon::create($request->year, 9, 30)->endOfMonth();
        } else if ($request->month == 'Q4') {
            $startDate = Carbon::create($request->year, 10, 1)->startOfMonth();
            $endDate = Carbon::create($request->year, 12, 31)->endOfMonth();
        }
    
        // Step 3: Fetch transactions based on date range and organization_id
        $transactions = Transactions::where('organization_id', $request->organization_id)
                                   ->whereBetween('date', [$startDate, $endDate])
                                   ->get();
    
        // Step 4: Attach transactions to the tax return only if there are any
        if ($transactions->isNotEmpty()) {
            $taxReturn->transactions()->attach($transactions->pluck('id'));
        }
    
        // Step 5: Redirect with success message
        return redirect()->route('vat_return')->with('success', 'Tax Return created successfully.' . ($transactions->isEmpty() ? ' No transactions found for the selected period.' : ' Transactions associated successfully.'));
    }
    

    /**
     * Display the specified resource.
     */
 

    public function showSlspData(TaxReturn $taxReturn)
    {
      
        $transactions = $taxReturn->transactions()->with('taxRows')->paginate(10); 
        return view('tax_return.vat_show', compact('taxReturn', 'transactions'));
    }
 public function showReport(TaxReturn $taxReturn)
{
    // Fetch the necessary data
    $transactions = $taxReturn->transactions()->with('taxRows')->get();

    // Generate the PDF from a Blade view
    $pdf = PDF::loadView('tax_return.vat_report_pdf', compact('taxReturn', 'transactions'));

    // Output the PDF as a string (base64 encode it to display in iframe)
    $pdfOutput = $pdf->output();

    // Pass the base64-encoded PDF to the view to be displayed in an iframe
    return view('tax_return.vat_report', [
        'taxReturn' => $taxReturn,
        'transactions' => $transactions,
        'pdfOutput' => base64_encode($pdfOutput), // Pass base64-encoded PDF
    ]);
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
