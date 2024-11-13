<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'transaction_id', 'payment_date', 'reference_number', 'bank_account', 'total_amount_paid'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transactions::class);
    }
}
