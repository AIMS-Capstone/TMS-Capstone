<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxOptionRate extends Model
{
    use HasFactory;

    protected $table = 'tax_option_rates';

    protected $fillable = [
        'organization_id', 
        'tax_return_id', 
        'rate_type' // 'graduated' or '8_percent'
    ];

    // Relationship to Organization
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    // Relationship to TaxReturn
    public function taxReturn()
    {
        return $this->belongsTo(TaxReturn::class);
    }
}
