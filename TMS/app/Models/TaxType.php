<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxType extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_type',
        'tax_type',
        'description',
        'VAT',

    ];
}
