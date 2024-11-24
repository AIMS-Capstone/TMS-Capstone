<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax2550Q extends Model
{
    use HasFactory;

    // Table name
    protected $table = '2550q';

    // Fillable columns to protect against mass-assignment vulnerabilities
    protected $fillable = [
        'period',
        'year_ended',
        'quarter',
        'return_from',
        'return_to',
        'amended_return',
        'short_period_return',
        'tin',
        'rdo_code',
        'taxpayer_name',
        'registered_address',
        'zip_code',
        'contact_number',
        'email_address',
        'taxpayer_classification',
        'tax_relief',
        'yes_specify',
        'vatable_sales',
        'vatable_sales_tax_amount',
        'zero_rated_sales',
        'exempt_sales',
        'total_sales',
        'total_output_tax',
        'uncollected_receivable_vat',
        'recovered_uncollected_receivables',
        'total_adjusted_output_tax',
        'input_carried_over',
        'input_tax_deferred',
        'transitional_input_tax',
        'presumptive_input_tax',
        'other_specify',
        'other_input_tax',
        'total_input_tax',
        'domestic_purchase',
        'domestic_purchase_input_tax',
        'services_non_resident',
        'services_non_resident_input_tax',
        'importations',
        'importations_input_tax',
        'purchases_others_specify',
        'purchases_others_specify_amount',
        'purchases_others_specify_input_tax',
        'domestic_no_input',
        'total_current_purchase',
        'total_current_purchase_input_tax',
        'total_available_input_tax',
        'importation_million_deferred_input_tax',
        'attributable_vat_exempt_input_tax',
        'vat_refund_input_tax',
        'unpaid_payables_input_tax',
        'other_deduction_specify',
        'other_deduction_specify_input_tax',
        'total_deductions_input_tax',
        'settled_unpaid_input_tax',
        'adjusted_deductions_input_tax',
        'total_allowable_input_Tax',
        'excess_input_tax',
        'creditable_vat_withheld',
        'advance_vat_payment',
        'vat_paid_if_amended',
        'other_credits_specify',
        'other_credits_specify_amount',
        'total_tax_credits',
        'tax_still_payable',
        'surcharge',
        'interest',
        'compromise',
        'total_penalties',
        'total_amount_payable',
    ];

    public function taxReturn()
    {
        return $this->belongsTo(TaxReturn::class, 'tax_return_id');
    }
}
