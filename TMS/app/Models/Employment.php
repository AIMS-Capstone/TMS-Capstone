<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employer_name',
        'employment_from',
        'employment_to',
        'rate',
        'rate_per_month',
        'employment_status',
        'reason_for_separation',
        'employee_wage_status',
        'substituted_filing',
        'with_previous_employer',
        'previous_employer_tin',
        'prev_employment_from',
        'prev_employment_to',
        'prev_employment_status',
        'employee_id',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }
}
