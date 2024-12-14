<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form1601EQAtcDetail extends Model
{
    use HasFactory;

    protected $table = '1601eq_atc_details';

    protected $fillable = [
        'form_1601eq_id',
        'atc_id',
        'tax_base',
        'tax_rate',
        'tax_withheld',
    ];

    /**
     * Relationship with Form1601EQ.
     */
    public function form1601EQ()
    {
        return $this->belongsTo(Form1601EQ::class, 'form_1601eq_id');
    }

    /**
     * Relationship with ATC.
     */
    public function atc()
    {
        return $this->belongsTo(Atc::class, 'atc_id');
    }
}
