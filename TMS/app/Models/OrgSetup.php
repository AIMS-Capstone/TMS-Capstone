<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrgSetup extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'org_setups';

    protected $fillable = [
        'type',
        'registration_name',
        'line_of_business',
        'address_line',
        'region',
        'province',
        'city',
        'zip_code',
        'contact_number',
        'email',
        'tin',
        'rdo',
        'tax_type',
        'registration_date',
        'start_date',
        'financial_year_end'
    ];
    public function account()
    {
        return $this->hasOne(OrgAccount::class, 'org_setup_id', 'id');
    }
    public function rdo()
    {
        return $this->belongsTo(RDO::class, 'rdo');
    }
}
