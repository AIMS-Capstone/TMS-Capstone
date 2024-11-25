<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndividualBackgroundInformation extends Model
{
    use HasFactory;

    protected $table = 'individual_background_information';

    protected $fillable = [
        'tax_return_id', 
        'date_of_birth', 
        'filer_type', 
        'alphanumeric_tax_code', 
        'civil_status', 
        'spouse_employment_status', 
        'spouse_tin', 
        'spouse_rdo', 
        'spouse_last_name', 
        'spouse_first_name', 
        'spouse_middle_name', 
        'spouse_filer_type'
    ];

    // Relationship to TaxReturn
    public function taxReturn()
    {
        return $this->belongsTo(TaxReturn::class);
    }
}
