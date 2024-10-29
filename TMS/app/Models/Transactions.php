<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;
    protected $fillable = ['transaction_type', 'date', 'inv_number', 'reference', 'total_amount', 'contact', 'itr_include', 'total_amount_credit', 'total_amount_debit', 'vatable_sales','vatable_purchase', 'vat_amount', 'non_vatable_sales','non_vatable_purchase','status', 'organization_id' ];

    public function taxRows()
    {
        return $this->hasMany(TaxRow::class, 'transaction_id');
    }
    public function contactDetails()
    {
        return $this->belongsTo(Contacts::class, 'contact');
    }
 
    public function taxReturns()
    {
        return $this->belongsToMany(TaxReturn::class, 'tax_return_transactions', 'transaction_id', 'tax_return_id')
                    ->withPivot('allocation_percentage') // If you want to access the allocation percentage
                    ->withTimestamps();
    }
    public function taxReturnTransactions()
{
    return $this->hasMany(TaxReturnTransaction::class, 'transaction_id');
}


}
