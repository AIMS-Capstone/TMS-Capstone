<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form1601EQAtcDetail extends Model
{
    use HasFactory;

    protected $table = '1601EQ_atc_details';

    protected $fillable = [
        'withholding_id', 
        'atc_id',
        'tax_base',
        'tax_rate',
        'tax_withheld',
    ];

    /**
     * Relationship with WithHolding.
     */
    public function withholding()
    {
        return $this->belongsTo(WithHolding::class, 'withholding_id');
    }

    /**
     * Relationship with ATC.
     */
    public function atc()
    {
        return $this->belongsTo(Atc::class, 'atc_id');
    }
}
