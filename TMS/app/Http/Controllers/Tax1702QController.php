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
        // Step 1: Validate incoming data
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
            // Additional fields...
            'total_tax_credits' => 'nullable|numeric',
        ]);

        try {
            // Log validation success
            activity('Validation Success')
                ->withProperties([
                    'tax_return_id' => $taxReturn->id,
                    'validated_data' => $validated,
                    'user_id' => Auth::user(),
                    'ip' => request()->ip(),
                ])
                ->log('Validation for Form 1702Q succeeded.');

            // Step 2: Find or create Tax1702Q for this tax return
            $tax1702q = Tax1702Q::where('tax_return_id', $taxReturn->id)->first();

            $messageType = 'success'; // Default message type for new record
            $messageText = 'Form 1702Q has been submitted successfully!'; // Default message for new record
        
            if ($tax1702q) {
                $messageType = 'success2'; // Set this to 'success2' for edited records
                $messageText = 'Form 1702Q has been updated successfully!'; // Message for updated record
            } else {
                // Create a new Tax1702Q if it doesn't exist
                $tax1702q = new Tax1702Q();
                $tax1702q->tax_return_id = $taxReturn->id;

                // Log creation of a new record
                activity('Tax1702Q Record Created')
                    ->performedOn($tax1702q)
                    ->causedBy(Auth::user())
                    ->withProperties([
                        'tax_return_id' => $taxReturn->id,
                        'ip' => request()->ip(),
                    ])
                    ->log('New Tax1702Q record created.');
            }

        // Step 3: Save the data
            $tax1702q->fill($validated);
            $tax1702q->save();

            // Log record update or save
            activity('Tax1702Q Record Updated')
                ->performedOn($tax1702q)
                ->causedBy(Auth::user())
                ->withProperties([
                    'tax_return_id' => $taxReturn->id,
                    'updated_data' => $validated,
                    'ip' => request()->ip(),
                ])
                ->log('Tax1702Q record updated successfully.');

            // Step 4: Redirect with success
            return redirect()
                ->route('tax_return.corporate_quarterly_pdf', ['taxReturn' => $taxReturn->id])
                ->with($messageType, $messageText);
        

        } catch (\Exception $e) {
            // Log error
            activity('Tax1702Q Submission Error')
                ->causedBy(Auth::user())
                ->withProperties([
                    'tax_return_id' => $taxReturn->id,
                    'error_message' => $e->getMessage(),
                    'ip' => request()->ip(),
                ])
                ->log('An error occurred while submitting Form 1702Q.');

            // Redirect back with error
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
        try {
            // Step 1: Retrieve data
            $tax1702q = Tax1702Q::where('tax_return_id', $tax_return_id)->firstOrFail();
            $taxReturn = TaxReturn::findOrFail($tax_return_id);

            // Log successful data retrieval
            activity('PDF Generation')
                ->causedBy(Auth::user())
                ->withProperties([
                    'tax_return_id' => $tax_return_id,
                    'tax1702q_id' => $tax1702q->id,
                    'user_id' => Auth::user(),
                    'ip' => request()->ip(),
                ])
                ->log('Retrieved data for PDF generation.');

            // Step 2: Generate PDF
            $pdf = PDF::loadView('tax_return.non_individual_income_pdf', [
                'tax1702q' => $tax1702q,
                'taxReturn' => $taxReturn,
            ]);

            $pdf->setPaper('legal', 'portrait');

            // Log successful PDF creation
            activity('PDF Download')
                ->causedBy(Auth::user())
                ->withProperties([
                    'tax_return_id' => $tax_return_id,
                    'tax1702q_id' => $tax1702q->id,
                    'file_name' => '1702Q_' . $tax1702q->quarter . '_' . $tax1702q->year_ended . '.pdf',
                    'ip' => request()->ip(),
                ])
                ->log('PDF generated and ready for download.');

            // Step 3: Return the PDF for download
            return $pdf->download('1702Q_' . $tax1702q->quarter . '_' . $tax1702q->year_ended . '.pdf');
        } catch (\Exception $e) {
            // Log errors during the process
            activity('PDF Download Error')
                ->causedBy(Auth::user())
                ->withProperties([
                    'tax_return_id' => $tax_return_id,
                    'error_message' => $e->getMessage(),
                    'ip' => request()->ip(),
                ])
                ->log('An error occurred while generating or downloading the PDF.');

            return redirect()
                ->back()
                ->with('error', 'An error occurred while generating the PDF. Please try again.');
        }
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