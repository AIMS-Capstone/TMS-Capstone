<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax2551QSchedule extends Model
{
    use HasFactory;

    protected $table = '2551q_schedule';

    protected $fillable = [
        '2551q_id',
        'atc_code',
        'tax_base',
        'tax_rate',
        'tax_due',
    ];

    // Relationship with 2551Q
    public function tax2551q()
    {
        return $this->belongsTo(Tax2551Q::class, '2551q_id');
    }
}
