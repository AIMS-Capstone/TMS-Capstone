<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpouseTaxOptionRate extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'spouse_tax_option_rate';

    // The attributes that are mass assignable
    protected $fillable = [
        'spouse_information_id', 
        'rate_type', 
        'deduction_method'
    ];

    // Relationship to SpouseInformation
    public function spouseInformation()
    {
        return $this->belongsTo(SpouseInformation::class);
    }
}
