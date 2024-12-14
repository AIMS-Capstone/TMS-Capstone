<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form1604E extends Model
{
    use HasFactory;

    protected $table = 'form1604_e_s'; 

    protected $fillable = [
        'withholding_id',
        'org_setup_id',
        'year',
        'amended_return',
        'number_of_sheets',
        'agent_category',
        'agent_top',
    ];

    /**
     * Relationship with WithHolding.
     */
    public function withholding()
    {
        return $this->belongsTo(WithHolding::class, 'withholding_id');
    }

    /**
     * Relationship with OrgSetup.
     */
    public function organization()
    {
        return $this->belongsTo(OrgSetup::class, 'org_setup_id');
    }

    /**
     * Get organization data for autofill purposes.
     */
    public function getOrganizationDataAttribute()
    {
        $orgSetup = $this->organization;
        return [
            'name' => $orgSetup->registration_name,
            'address' => $orgSetup->address_line,
            'zip_code' => $orgSetup->zip_code,
            'contact_number' => $orgSetup->contact_number,
            'email' => $orgSetup->email,
        ];
    }
}
