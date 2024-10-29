<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxReturnTransaction extends Model
{
    use HasFactory;

    protected $table = 'tax_return_transactions';

    protected $fillable = [
        'tax_return_id', 
        'transaction_id', 
        'allocation_percentage'
    ];

    // Define the relationship to TaxReturn
    public function taxReturn()
    {
        return $this->belongsTo(TaxReturn::class, 'tax_return_id');
    }

    // Define the relationship to Transaction
    public function transaction()
    {
        return $this->belongsTo(Transactions::class, 'transaction_id');
    }

    // Define the relationship to TaxRows with filtering on tax_type
    public function taxRows()
    {
        return $this->hasMany(TaxRow::class, 'transaction_id', 'transaction_id');
    }
}
