<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrgSetup extends Model
{
    use HasFactory;
    protected $table = 'org_setups';

    protected $fillable = [
        'type',
        'registration_name',
        'line_of_business',
        'address_line',
        'region',
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
}
