<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tax1701Q;
use App\Models\TaxReturn;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Auth;

class Tax1701QController extends Controller
{
    public function store1701Q(Request $request, TaxReturn $taxReturn)
    {
        // Step 1: Validate incoming data
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
            'individual_deduction_method' => 'nullable|string',
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
            'other_tax_credits_specify' => 'nullable|numeric',
            'total_tax_credits' => 'nullable|numeric',
            'tax_payable' => 'nullable|numeric',
            'surcharge' => 'nullable|numeric',
            'interest' => 'nullable|numeric',
            'compromise' => 'nullable|numeric',
            'total_penalties' => 'nullable|numeric',
            'total_amount_payable' => 'nullable|numeric',
            'graduated_non_op_specify' => 'nullable|string',
            'graduated_non_op' => 'nullable|numeric',
            'partner_gpp' => 'nullable|numeric',
            'graduated_total_taxable_income' => 'nullable|numeric',
            'tax_due_graduated' => 'nullable|numeric',
        ]);

        try {
            // Log validation success
            activity('Validation Success')
                ->causedBy(Auth::user())
                ->withProperties([
                    'tax_return_id' => $taxReturn->id,
                    'validated_data' => $validatedData,
                    'ip' => request()->ip(),
                ])
                ->log('Validation for Tax1701Q succeeded.');

            // Step 2: Find or create the Tax1701Q record
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

                // Log record creation
                activity('Tax1701Q Record Created')
                    ->performedOn($tax1701Q)
                    ->causedBy(Auth::user())
                    ->withProperties([
                        'tax_return_id' => $taxReturn->id,
                        'ip' => request()->ip(),
                    ])
                    ->log('New Tax1701Q record created.');

            // Step 3: Save the data
            $tax1701Q->fill($validatedData);
            $tax1701Q->save();

            // Log record update
            activity('Tax1701Q Record Updated')
                ->performedOn($tax1701Q)
                ->causedBy(Auth::user())
                ->withProperties([
                    'tax_return_id' => $taxReturn->id,
                    'updated_data' => $validatedData,
                    'ip' => request()->ip(),
                ])
                ->log('Tax1701Q record updated successfully.');

            // Redirect with success message
            return redirect()
                ->route('income_return.reportPDF', ['taxReturn' => $taxReturn->id])
                ->with($messageType, $messageText);
        } catch (\Exception $e) {
            // Log error
            activity('Tax1701Q Submission Error')
                ->causedBy(Auth::user())
                ->withProperties([
                    'tax_return_id' => $taxReturn->id,
                    'error_message' => $e->getMessage(),
                    'ip' => request()->ip(),
                ])
                ->log('An error occurred while saving Tax1701Q data.');

            // Redirect back with error message
            return redirect()
                ->back()
                ->with('error', 'An error occurred while saving the data. Please try again.')
                ->withInput();
        }
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
        try {
            // Step 1: Retrieve data
            $tax1701q = Tax1701Q::where('tax_return_id', $tax_return_id)->firstOrFail();
            $taxReturn = TaxReturn::findOrFail($tax_return_id);

            // Log successful data retrieval
            activity('PDF Generation')
                ->causedBy(Auth::user())
                ->withProperties([
                    'tax_return_id' => $tax_return_id,
                    'tax1701q_id' => $tax1701q->id,
                    'user_id' => Auth::user(),
                    'ip' => request()->ip(),
                ])
                ->log('Retrieved data for 1701Q PDF generation.');

            // Step 2: Generate PDF
            $pdf = PDF::loadView('tax_return.individual_income_pdf', [
                'tax1701q' => $tax1701q,
                'taxReturn' => $taxReturn,
            ]);

            $pdf->setPaper('legal', 'portrait');

            // Log successful PDF creation
            activity('PDF Download')
                ->causedBy(Auth::user())
                ->withProperties([
                    'tax_return_id' => $tax_return_id,
                    'tax1701q_id' => $tax1701q->id,
                    'file_name' => '1701Q_' . $tax1701q->quarter . '_' . $tax1701q->for_the_year . '.pdf',
                    'ip' => request()->ip(),
                ])
                ->log('PDF generated and ready for download.');

            // Step 3: Return the PDF for download
            return $pdf->download('1701Q_' . $tax1701q->quarter . '_' . $tax1701q->for_the_year . '.pdf');
        } catch (\Exception $e) {
            // Log errors during the process
            activity('PDF Download Error')
                ->causedBy(Auth::user())
                ->withProperties([
                    'tax_return_id' => $tax_return_id,
                    'error_message' => $e->getMessage(),
                    'ip' => request()->ip(),
                ])
                ->log('An error occurred during 1701Q PDF generation or download.');

            // Redirect back with an error message
            return redirect()
                ->back()
                ->with('error', 'An error occurred while generating the PDF. Please try again.');
        }
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