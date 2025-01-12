<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax1702Q extends Model
{
    protected $table = '1702q';

    protected $fillable = [
        'tax_return_id',
        'period',
        'year_ended',
        'quarter',
        'amended_return',
        'alphanumeric_tax_code',
        'tin',
        'rdo_code',
        'taxpayer_name',
        'registered_address',
        'zip_code',
        'contact_number',
        'email_address',
        'tax_relief',
        'yes_specify',
        'show_income_tax_due_regular',
        'unexpired_excess_mcit',
        'balance_tax_due_regular',
        'show_income_tax_due_special',
        'aggregate_tax_due',
        'show_total_tax_credits',
        'net_tax_payable',
        'surcharge',
        'interest',
        'compromise',
        'total_penalties',
        'total_amount_payable',
        'sales_receipts_special',
        'cost_of_sales_special',
        'gross_income_special',
        'other_taxable_income_special',
        'total_gross_income_special',
        'deductions_special',
        'taxable_income_quarter_special',
        'prev_quarter_income_special',
        'total_taxable_income_special',
        'tax_rate_special',
        'income_tax_due_special',
        'other_agencies_share_special',
        'net_tax_due_special',
        'sales_receipts_regular',
        'cost_of_sales_regular',
        'gross_income_operation_regular',
        'non_operating_income_regular',
        'total_gross_income_regular',
        'deductions_regular',
        'taxable_income_quarter_regular',
        'taxable_income_previous_regular',
        'total_taxable_income_regular',
        'income_tax_rate_regular',
        'income_tax_due_regular',
        'mcit_regular',
        'final_income_tax_due_regular',
        'gross_income_first_quarter_mcit',
        'gross_income_second_quarter_mcit',
        'gross_income_third_quarter_mcit',
        'total_gross_income_mcit',
        'mcit_rate',
        'minimum_corporate_income_tax_mcit',
        'prior_year_excess_credits',
        'previous_quarters_tax_payments',
        'previous_quarters_mcit_payments',
        'previous_quarters_creditable_tax',
        'current_quarter_creditable_tax',
        'previously_filed_tax_payment',
        'other_tax_specify',
        'other_tax_amount',
        'other_tax_specify2',
        'other_tax_amount2',
        'total_tax_credits'
    ];

    /**
     * Get the tax return that owns this 1702Q form.
     */
    public function taxReturn()
    {
        return $this->belongsTo(TaxReturn::class);
    }
}