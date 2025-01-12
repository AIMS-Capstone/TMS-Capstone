<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax2551Q extends Model
{
    use HasFactory;

    protected $table = '2551q';  // The table where the data will be stored

    // The attributes that are mass assignable
    protected $fillable = [
        'tax_return_id', 
        'period',
        'year_ended',
        'quarter',
        'amended_return',
        'sheets_attached',
        'tin',
        'rdo_code',
        'taxpayer_name',
        'registered_address',
        'zip_code',
        'contact_number',
        'email_address',
        'tax_relief',
        'yes_specify',
        'availed_tax_rate',
        'tax_due',
        'creditable_tax',
        'amended_tax',
        'other_tax_specify',
        'other_tax_amount',
        'total_tax_credits',
        'tax_still_payable',
        'surcharge',
        'interest',
        'compromise',
        'total_penalties',
        'total_amount_payable',
    ];

    // Relationship with Schedule 1 (Schedule data)
    public function schedule1()
    {
        return $this->hasMany(Tax2551QSchedule::class, '2551q_id');
    }

    // Relationship with Tax Return (The tax return that this record belongs to)
    public function taxReturn()
    {
        return $this->belongsTo(TaxReturn::class, 'tax_return_id');
    }
}
