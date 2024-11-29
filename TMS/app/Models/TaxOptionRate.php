<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxOptionRate extends Model
{
    use HasFactory;

    protected $table = 'tax_option_rate';

    protected $fillable = [
        'organization_id', 
        'individual_background_information_id', 
        'rate_type',  // 'graduated' or '8_percent'
        'deduction_method',  // New column
    ];

    // Relationship to Organization
    public function organization()
    {
        return $this->belongsTo(OrgSetup::class);
    }

    // Relationship to TaxReturn
    public function taxReturn()
    {
        return $this->belongsTo(TaxReturn::class);
    }
}
