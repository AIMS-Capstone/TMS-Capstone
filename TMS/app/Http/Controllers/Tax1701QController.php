<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tax1701Q;
use App\Models\TaxReturn;

class Tax1701QController extends Controller
{
    /**
     * Store the submitted Tax1701Q form data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TaxReturn  $taxReturn
     * @return \Illuminate\Http\Response
     */
    public function store1701Q(Request $request, TaxReturn $taxReturn)
    {
        // dd($request); // Uncomment this line if you want to debug the request

        // Validate the incoming request data
        $validatedData = $request->validate([
            'for_the_year' => 'nullable|string|max:4',
            'quarter' => 'nullable|string|max:20',
            'amended_return' => 'nullable|string|max:3',
            'sheets' => 'nullable|integer',
            'tin' => 'nullable|string|max:20',
            'rdo_code' => 'nullable|string|max:10',
            'filer_type' => 'nullable|string|max:50',
            'alphanumeric_tax_code' => 'nullable|string|max:10',
            'taxpayer_name' => 'nullable|string|max:255',
            'registered_address' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:10',
            'date_of_birth' => 'nullable|date',
            'email_address' => 'nullable|email|max:255',
            'citizenship' => 'nullable|string|max:50',
            'foreign_tax' => 'nullable|string|max:50',
            'claiming_foreign_credits' => 'nullable|boolean',
            'individual_rate_type' => 'nullable|string|max:50',

          

            // Tax calculations
            'show_tax_due' => 'nullable|numeric',
        
            'show_tax_credits_payments' => 'nullable|numeric',
     
            'show_tax_payable' => 'nullable|numeric',
          
            'show_total_penalties' => 'nullable|numeric',
           
            'show_total_amount_payable' => 'nullable|numeric',
          
            'aggregate_amount_payable' => 'nullable|numeric',

            // Sales and Revenues
            'sales_revenues' => 'nullable|numeric',
          
            'cost_of_sales' => 'nullable|numeric',
         
            'gross_income' => 'nullable|numeric',
          
            'total_itemized_deductions' => 'nullable|numeric',
          
            'osd' => 'nullable|numeric',
         
            'net_income' => 'nullable|numeric',
           
            'taxable_income' => 'nullable|numeric',
          

            // Fields ending with _8
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
            'total_tax_credits' => 'nullable|numeric',
            'tax_payable' => 'nullable|numeric',
            'surcharge' => 'nullable|numeric',
            'interest' => 'nullable|numeric',
            'compromise' => 'nullable|numeric',
            'total_penalties' => 'nullable|numeric',
        ]);

        // Store or update the Tax1701Q data
        $tax1701Q = $taxReturn->tax1701q ?: new Tax1701Q();

        // Fill the validated data into the Tax1701Q model
        $tax1701Q->fill($validatedData);
        $tax1701Q->tax_return_id = $taxReturn->id; // Assign the foreign key to the tax_return

        // Save the tax1701Q instance
        $tax1701Q->save();

        // Redirect back with a success message
        return redirect()->route('tax_return.show', ['taxReturn' => $taxReturn->id])
                         ->with('success', 'Tax1701Q data has been saved successfully!');
    }
}
