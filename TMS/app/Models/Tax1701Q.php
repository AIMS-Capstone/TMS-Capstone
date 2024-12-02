<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax1701Q extends Model
{
    use HasFactory;
    protected $table = 'tax_1701q'; // Specify the table name
    protected $fillable = [
        'for_the_year', 'quarter', 'amended_return', 'sheets', 'tin', 'rdo_code', 'filer_type', 
        'alphanumeric_tax_code', 'taxpayer_name', 'registered_address', 'zip_code', 'date_of_birth', 
        'email_address', 'citizenship', 'foreign_tax', 'claiming_foreign_credits', 'individual_rate_type', 
        'spouse_tin', 'spouse_rdo', 'spouse_filer_type', 'spouse_alphanumeric_tax_code', 'spouse_name', 
        'spouse_citizenship', 'spouse_foreign_tax_number', 'spouse_claiming_foreign_credits', 
        'show_tax_due', 'show_spouse_tax_due', 'show_tax_credits_payments', 'show_spouse_tax_credits_payments', 
        'show_tax_payable', 'show_spouse_tax_payable', 'show_total_penalties', 'show_spouse_total_penalties', 
        'show_total_amount_payable', 'show_spouse_total_amount_payable', 'aggregate_amount_payable',
        'sales_revenues', 'spouse_sales_revenues', 'cost_of_sales', 'spouse_cost_of_sales', 'gross_income', 
        'spouse_gross_income', 'total_itemized_deductions', 'spouse_total_itemized_deductions', 'osd', 
        'spouse_osd', 'net_income', 'spouse_net_income', 'taxable_income', 'spouse_taxable_income', 
        'sales_revenues_8', 'spouse_sales_revenues_8', 'non_op_specify_8', 'non_operating_8', 
        'spouse_non_operating_8', 'total_income_8', 'spouse_total_income_8', 'total_prev_8', 
        'spouse_total_prev_8', 'cumulative_taxable_income_8', 'spouse_cumulative_taxable_income_8', 
        'allowable_reduction_8', 'spouse_allowable_reduction_8', 'taxable_income_8', 'spouse_taxable_income_8', 
        'tax_due_8', 'spouse_tax_due_8', 'prior_year_credits', 'spouse_prior_year_credits', 
        'tax_payments_prev_quarters', 'spouse_tax_payments_prev_quarters', 
        'creditable_tax_withheld_prev_quarters', 'spouse_creditable_tax_withheld_prev_quarters', 
        'creditable_tax_withheld_bir', 'spouse_creditable_tax_withheld_bir', 'tax_paid_prev_return', 
        'spouse_tax_paid_prev_return', 'foreign_tax_credits', 'spouse_foreign_tax_credits', 
        'other_tax_credits', 'spouse_other_tax_credits', 'total_tax_credits', 'spouse_total_tax_credits', 
        'tax_payable', 'spouse_tax_payable', 'surcharge', 'spouse_surcharge', 'interest', 'spouse_interest', 
        'compromise', 'spouse_compromise', 'total_penalties', 'spouse_total_penalties'
    ];

    public function taxReturn()
    {
        return $this->belongsTo(TaxReturn::class, 'tax_return_id');
    }
}
