<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndividualTransaction extends Model
{
    use HasFactory;

    protected $table = 'individual_transactions';

    protected $fillable = [
        'tax_return_id', 
        'transaction_id', 
        'amount', 
        'description'
    ];

    // Relationship to TaxReturn
    public function taxReturn()
    {
        return $this->belongsTo(TaxReturn::class);
    }

    // Relationship to Transaction
    public function transaction()
    {
        return $this->belongsTo(Transactions::class);
    }
}
