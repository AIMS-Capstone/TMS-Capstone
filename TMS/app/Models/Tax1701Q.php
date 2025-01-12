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
        'show_tax_due','show_tax_credits_payments',
        'show_tax_payable', 'show_total_penalties',
        'show_total_amount_payable','aggregate_amount_payable',
        'sales_revenues', 'cost_of_sales','gross_income', 
        'total_itemized_deductions','osd', 
        'net_income','taxable_income',
        'sales_revenues_8','non_op_specify_8', 'non_operating_8', 
        'total_income_8','total_prev_8', 
 'cumulative_taxable_income_8', 
        'allowable_reduction_8',  'taxable_income_8', 
        'tax_due_8','prior_year_credits',
        'tax_payments_prev_quarters', 
        'creditable_tax_withheld_prev_quarters', 
        'creditable_tax_withheld_bir','tax_paid_prev_return', 
         'foreign_tax_credits',
        'other_tax_credits', 'total_tax_credits',
        'tax_payable','surcharge','interest', 
        'compromise','total_penalties','graduated_non_op','partner_gpp',
        'graduated_total_taxable_income','tax_due_graduated','graduated_non_op_specify',
        'total_amount_payable','individual_deduction_method'
    ];

    public function taxReturn()
    {
        return $this->belongsTo(TaxReturn::class, 'tax_return_id');
    }
}
