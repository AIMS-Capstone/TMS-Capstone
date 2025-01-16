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
        $validatedData = $request->validate([
            'for_the_year' => 'required|string|max:4',
            'quarter' => 'required|string|max:20',
            'amended_return' => 'required|string|max:3',
            'sheets' => 'required|integer',
            'tin' => 'required|string|max:20',
            'rdo_code' => 'required|string|max:10',
            'filer_type' => 'required|string|max:50',
            'alphanumeric_tax_code' => 'required|string|max:10',
            'taxpayer_name' => 'required|string|max:255',
            'registered_address' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10',
            'date_of_birth' => 'required|date',
            'email_address' => 'required|email|max:255',
            'citizenship' => 'required|string|max:50',
            'foreign_tax' => 'nullable|string|max:50',
            'claiming_foreign_credits' => 'required|boolean',
            'individual_rate_type' => 'required|string|max:50',
            'show_tax_due' => 'nullable|numeric',
            'show_tax_credits_payments' => 'nullable|numeric',
            'show_tax_payable' => 'nullable|numeric',
            'show_total_penalties' => 'nullable|numeric',
            'show_total_amount_payable' => 'nullable|numeric',
            'aggregate_amount_payable' => 'nullable|numeric',
            'sales_revenues' => 'nullable|numeric',
            'cost_of_sales' => 'nullable|numeric',
            'individual_deduction_method'=>'nullable|string',
            'gross_income' => 'nullable|numeric',
            'total_itemized_deductions' => 'nullable|numeric',
            'osd' => 'nullable|numeric',
            'net_income' => 'nullable|numeric',
            'taxable_income' => 'nullable|numeric',
            'sales_revenues_8' => 'nullable|numeric',
            'non_op_specify_8' => 'nullable|numeric',
            'non_operating_8' => 'nullable|numeric',
            'total_income_8' => 'nullable|numeric',
            'total_prev_8' => 'nullable|numeric',
            'cumulative_taxable_income_8' => 'nullable|numeric',
            'allowable_reduction_8' => 'nullable|numeric',
            'taxable_income_8' => 'nullable|numeric',
            'tax_due_8' => 'nullable|numeric',
            'prior_year_credits' => 'nullable|numeric',
            'tax_payments_prev_quarters' => 'nullable|numeric',
            'creditable_tax_withheld_prev_quarters' => 'nullable|numeric',
            'creditable_tax_withheld_bir' => 'nullable|numeric',
            'tax_paid_prev_return' => 'nullable|numeric',
            'foreign_tax_credits' => 'nullable|numeric',
            'other_tax_credits' => 'nullable|numeric',
            'other_tax_credits_specify'=> 'nullable|numeric',
            'total_tax_credits' => 'nullable|numeric',
            'tax_payable' => 'nullable|numeric',
            'surcharge' => 'nullable|numeric',
            'interest' => 'nullable|numeric',
            'compromise' => 'nullable|numeric',
            'total_penalties' => 'nullable|numeric',
            'total_amount_payable'=>'nullable|numeric',
            'graduated_non_op_specify' => 'nullable|string',
            'graduated_non_op'=>'nullable|numeric',
            'partner_gpp'=>'nullable|numeric',
            'graduated_total_taxable_income'=>'nullable|numeric',
            'tax_due_graduated'=>'nullable|numeric',
        ]);

        // Store or update the Tax1701Q data
        $tax1701Q = Tax1701Q::where('tax_return_id', $taxReturn->id)->first();

        $messageType = 'success'; // Default message type for new record
        $messageText = 'Tax1701Q data has been saved successfully!'; // Default message for new record
    
        if ($tax1701Q) {
            $messageType = 'success2'; // Set this to 'success2' for edited records
            $messageText = 'Tax1701Q data has been updated successfully!'; // Message for updated record
        } else {
            // Create a new Tax1701Q if it doesn't exist
            $tax1701Q = new Tax1701Q();
            $tax1701Q->tax_return_id = $taxReturn->id;
        }
        
        $tax1701Q->fill($validatedData);
        $tax1701Q->save();
        

 
        // Redirect to the report view page
        return redirect()->route('income_return.reportPDF', ['taxReturn' => $taxReturn->id])
        ->with($messageType, $messageText);
    }
    public function edit(TaxReturn $taxReturn)
{
    $tax1701q = Tax1701Q::where('tax_return_id', $taxReturn->id)->firstOrFail();
    
    return view('tax_return.income_report_edit', [
        'taxReturn' => $taxReturn,
        'tax1701q' => $tax1701q
    ]);
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