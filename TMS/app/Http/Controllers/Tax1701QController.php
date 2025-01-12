<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tax1701Q;
use App\Models\TaxReturn;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class Tax1701QController extends Controller
{
    public function store1701Q(Request $request, TaxReturn $taxReturn)
    {
        // Your existing validation code remains the same
        $validatedData = $request->validate([
            // ... your existing validation rules
        ]);

        // Store or update the Tax1701Q data
        $tax1701Q = $taxReturn->tax1701q ?: new Tax1701Q();
        $tax1701Q->fill($validatedData);
        $tax1701Q->tax_return_id = $taxReturn->id;
        $tax1701Q->save();

        // Redirect to the report view page
        return redirect()->route('income_return.reportPDF', ['taxReturn' => $taxReturn->id])
                        ->with('success', 'Tax1701Q data has been saved successfully!');
    }

    public function reportPDF(TaxReturn $taxReturn)
    {
        $tax1701q = Tax1701Q::where('tax_return_id', $taxReturn->id)->firstOrFail();

        // Return the view that contains the iframe
        return view('tax_return.income_report_pdf', [
            'taxReturn' => $taxReturn,
            'tax1701q' => $tax1701q
        ]);
    }

    public function downloadPdf($tax_return_id)
    {
        $tax1701q = Tax1701Q::where('tax_return_id', $tax_return_id)->firstOrFail();
        $taxReturn = TaxReturn::findOrFail($tax_return_id);

        $pdf = PDF::loadView('tax_return.individual_income_pdf', [
            'tax1701q' => $tax1701q,
            'taxReturn' => $taxReturn
        ]);

        $pdf->setPaper('legal', 'portrait');

        return $pdf->download('1701Q_' . $tax1701q->quarter . '_' . $tax1701q->for_the_year . '.pdf');
    }

    public function streamPdf($tax_return_id)
    {
        $tax1701q = Tax1701Q::where('tax_return_id', $tax_return_id)->firstOrFail();
        $taxReturn = TaxReturn::findOrFail($tax_return_id);

        $pdf = PDF::loadView('tax_return.individual_income_pdf', [
            'tax1701q' => $tax1701q,
            'taxReturn' => $taxReturn
        ]);

        $pdf->setPaper('legal', 'portrait');

        return $pdf->stream('1701Q_' . $tax1701q->quarter . '_' . $tax1701q->for_the_year . '.pdf');
    }
}