<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;
    protected $fillable = ['transaction_type', 'date', 'inv_number', 'reference', 'total_amount'];
    public function taxRows()
    {
        return $this->hasMany(TaxRow::class, 'transaction_id');
    }

}
