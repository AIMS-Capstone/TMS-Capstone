<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxRow extends Model
{
    use HasFactory;
    protected $fillable = ['transaction_id', 'amount', 'tax_code', 'tax_amount', 'net_amount', 'coa', 'tax_type', 'debit', 'credit', 'description', 'atc_amount'];

    public function transaction()
    {
        return $this->belongsTo(Transactions::class, 'transaction_id');
    }
    public function atc()
    {
        return $this->belongsTo(ATC::class, 'tax_code');
    }
    public function taxType()
    {
        return $this->belongsTo(TaxType::class, 'tax_type');
    }
    public function coaAccount()
    {
        return $this->belongsTo(Coa::class, 'coa'); // 'coa' is the foreign key in the TaxRow table
    }
}
