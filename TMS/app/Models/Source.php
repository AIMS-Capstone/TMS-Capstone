<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use HasFactory;

    protected $table = 'sources';

    protected $fillable = [
        'withholding_id',
        'employee_id',
        'employment_id',
        'payment_date',
        'gross_compensation',
        'taxable_compensation',
        'tax_due',
        'statutory_minimum_wage',
        'holiday_pay',
        'overtime_pay',
        'night_shift_differential',
        'hazard_pay',
        'month_13_pay',
        'de_minimis_benefits',
        'sss_gsis_phic_hdmf_union_dues',
        'other_non_taxable_compensation',
        'status',
    ];

    public function withholding()
    {
        return $this->belongsTo(WithHolding::class, 'withholding_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function employment()
    {
        return $this->belongsTo(Employment::class, 'employment_id');
    }

    public function getEmployeeWageStatusAttribute()
    {
        return $this->employee->latestEmployment->employee_wage_status ?? 'N/A';
    }
}
