<?php

namespace App\Http\Controllers;

use App\Models\Tax1702Q;
use App\Models\TaxReturn;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Auth;

class Tax1702QController extends Controller
{
    public function store(Request $request, TaxReturn $taxReturn)
    {
        $validated = $request->validate([
            'period' => 'required|string',
            'year_ended' => 'required|string',
            'quarter' => 'required|string',
            'amended_return' => 'required|string',
            'alphanumeric_tax_code' => 'required|string',
            'tin' => 'required|string',
            'rdo_code' => 'required|string',
            'taxpayer_name' => 'required|string',
            'registered_address' => 'required|string',
            'zip_code' => 'required|string',
            'contact_number' => 'required|string',
            'email_address' => 'required|email',
            'tax_relief' => 'required|string',
            'yes_specify' => 'nullable|string',
            
            // Tax calculations
            'show_income_tax_due_regular' => 'nullable|numeric',
            'unexpired_excess_mcit' => 'nullable|numeric',
            'balance_tax_due_regular' => 'nullable|numeric',
            'show_income_tax_due_special' => 'nullable|numeric',
            'aggregate_tax_due' => 'nullable|numeric',
            'show_total_tax_credits' => 'nullable|numeric',
            'net_tax_payable' => 'nullable|numeric',
            
            // Penalties
            'surcharge' => 'nullable|numeric',
            'interest' => 'nullable|numeric',
            'compromise' => 'nullable|numeric',
            'total_penalties' => 'nullable|numeric',
            'total_amount_payable' => 'nullable|numeric',
            
            // Special rates
            'sales_receipts_special' => 'nullable|numeric',
            'cost_of_sales_special' => 'nullable|numeric',
            'gross_income_special' => 'nullable|numeric',
            'other_taxable_income_special' => 'nullable|numeric',
            'total_gross_income_special' => 'nullable|numeric',
            'deductions_special' => 'nullable|numeric',
            'taxable_income_quarter_special' => 'nullable|numeric',
            'prev_quarter_income_special' => 'nullable|numeric',
            'total_taxable_income_special' => 'nullable|numeric',
            'tax_rate_special' => 'nullable|numeric',
            'income_tax_due_special' => 'nullable|numeric',
            'other_agencies_share_special' => 'nullable|numeric',
            'net_tax_due_special' => 'nullable|numeric',
            
            // Regular rates
            'sales_receipts_regular' => 'nullable|numeric',
            'cost_of_sales_regular' => 'nullable|numeric',
            'gross_income_operation_regular' => 'nullable|numeric',
            'non_operating_income_regular' => 'nullable|numeric',
            'total_gross_income_regular' => 'nullable|numeric',
            'deductions_regular' => 'nullable|numeric',
            'taxable_income_quarter_regular' => 'nullable|numeric',
            'taxable_income_previous_regular' => 'nullable|numeric',
            'total_taxable_income_regular' => 'nullable|numeric',
            'income_tax_rate_regular' => 'nullable|numeric',
            'income_tax_due_regular' => 'nullable|numeric',
            'mcit_regular' => 'nullable|numeric',
            'final_income_tax_due_regular' => 'nullable|numeric',
            
            // MCIT
            'gross_income_first_quarter_mcit' => 'nullable|numeric',
            'gross_income_second_quarter_mcit' => 'nullable|numeric',
            'gross_income_third_quarter_mcit' => 'nullable|numeric',
            'total_gross_income_mcit' => 'nullable|numeric',
            'mcit_rate' => 'nullable|numeric',
            'minimum_corporate_income_tax_mcit' => 'nullable|numeric',
            
            // Tax credits
            'prior_year_excess_credits' => 'nullable|numeric',
            'previous_quarters_tax_payments' => 'nullable|numeric',
            'previous_quarters_mcit_payments' => 'nullable|numeric',
            'previous_quarters_creditable_tax' => 'nullable|numeric',
            'current_quarter_creditable_tax' => 'nullable|numeric',
            'previously_filed_tax_payment' => 'nullable|numeric',
            
            // Other tax credits
            'other_tax_specify' => 'nullable|string',
            'other_tax_amount' => 'nullable|numeric',
            'other_tax_specify2' => 'nullable|string',
            'other_tax_amount2' => 'nullable|numeric',
            'total_tax_credits' => 'nullable|numeric'
        ]);

        try {
            activity('Validation Success')
                ->withProperties([
                    'tax_return_id' => $taxReturn->id,
                    'validated_data' => $validated,
                    'user_id' => Auth::user(),
                    'ip' => request()->ip(),
                ])
                ->log('Validation for Form 1702Q succeeded.');
            // Find or create Tax1702Q for this tax return
            $tax1702q = Tax1702Q::where('tax_return_id', $taxReturn->id)->first();

            $messageType = 'success'; // Default message type for new record
            $messageText = 'Form 1702Q has been submitted successfully!'; // Default message for new record
        
            if ($tax1702q) {
                $messageType = 'success2'; // Set this to 'success2' for edited records
                $messageText = 'Form 1702Q has been updated successfully!'; // Message for updated record
                activity('Tax1702Q Record Updated')
                ->performedOn($tax1702q)
                ->causedBy(Auth::user())
                ->withProperties([
                    'tax_return_id' => $taxReturn->id,
                    'updated_data' => $validated,
                    'ip' => request()->ip(),
                ])
                ->log('Tax1702Q record updated successfully.');
            } else {
                // Create a new Tax1702Q if it doesn't exist
                $tax1702q = new Tax1702Q();
                $tax1702q->tax_return_id = $taxReturn->id;
                activity('Tax1702Q Record Created')
                ->performedOn($tax1702q)
                ->causedBy(Auth::user())
                ->withProperties([
                    'tax_return_id' => $taxReturn->id,
                    'ip' => request()->ip(),
                ])
                ->log('New Tax1702Q record created.');
            }
            
        
            $tax1702q->fill($validated);
            $tax1702q->save();
        
            // Redirect to the report view page with appropriate message
            return redirect()
                ->route('tax_return.corporate_quarterly_pdf', ['taxReturn' => $taxReturn->id])
                ->with($messageType, $messageText);
        

        } catch (\Exception $e) {
            activity('Tax1702Q Submission Error')
            ->causedBy(Auth::user())
            ->withProperties([
                'tax_return_id' => $taxReturn->id,
                'error_message' => $e->getMessage(),
                'ip' => request()->ip(),
            ])
            ->log('An error occurred while submitting Form 1702Q.');
            return redirect()
                ->back()
                ->with('error', 'An error occurred while submitting the form.')
                ->withInput();
        }
    }

    public function edit(TaxReturn $taxReturn)
    {
        $tax1702q = Tax1702Q::where('tax_return_id', $taxReturn->id)->firstOrFail();
        
        return view('1702q.edit', [
            'taxReturn' => $taxReturn,
            'tax1702q' => $tax1702q
        ]);
    }

    public function reportPDF(TaxReturn $taxReturn)
    {
        $tax1702q = Tax1702Q::where('tax_return_id', $taxReturn->id)->firstOrFail();

        // Return the view that contains the iframe
        return view('tax_return.non_individual_income_report', [
            'taxReturn' => $taxReturn,
            'tax1702q' => $tax1702q
        ]);
    }

    public function downloadPdf($tax_return_id)
    {
        $tax1702q = Tax1702Q::where('tax_return_id', $tax_return_id)->firstOrFail();
        $taxReturn = TaxReturn::findOrFail($tax_return_id);
        activity('PDF Generation')
        ->causedBy(Auth::user())
        ->withProperties([
            'tax_return_id' => $tax_return_id,
            'tax1702q_id' => $tax1702q->id,
            'user_id' => Auth::user(),
            'ip' => request()->ip(),
        ])
        ->log('Retrieved data for PDF generation.');

        $pdf = PDF::loadView('tax_return.non_individual_income_pdf', [
            'tax1702q' => $tax1702q,
            'taxReturn' => $taxReturn
        ]);

        

        $pdf->setPaper('legal', 'portrait');
        activity('PDF Download')
        ->causedBy(Auth::user())
        ->withProperties([
            'tax_return_id' => $tax_return_id,
            'tax1702q_id' => $tax1702q->id,
            'file_name' => '1702Q_' . $tax1702q->quarter . '_' . $tax1702q->year_ended . '.pdf',
            'ip' => request()->ip(),
        ])
        ->log('PDF generated and ready for download.');

        return $pdf->download('1702Q_' . $tax1702q->quarter . '_' . $tax1702q->year_ended . '.pdf');
    }

    public function streamPdf($tax_return_id)
    {
        $tax1702q = Tax1702Q::where('tax_return_id', $tax_return_id)->firstOrFail();
        $taxReturn = TaxReturn::findOrFail($tax_return_id);

        $pdf = PDF::loadView('tax_return.non_individual_income_pdf', [
            'tax1702q' => $tax1702q,
            'taxReturn' => $taxReturn
        ]);

        $pdf->setPaper('legal', 'portrait');

        return $pdf->stream('1702Q_' . $tax1702q->quarter . '_' . $tax1702q->year_ended . '.pdf');
    }
}