<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class atc extends Model
{
    use HasFactory;
    protected $fillable = [
        'tax_code',
        'transaction_type',
        'category',
        'coverage',
        'description',
        'type',
        'tax_rate',
    ];
    
}
