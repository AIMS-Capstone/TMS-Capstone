<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form0619E extends Model
{
    use HasFactory;

    protected $table = '0619E_forms';

    protected $fillable = [
        'org_setup_id',
        'withholding_id',
        'for_month',
        'due_date',
        'amended_return',
        'any_taxes_withheld',
        'tax_code',
        'atc_id',
        'category',
        'amount_of_remittance',
        'remitted_previous',
        'net_amount_of_remittance',
        'surcharge',
        'interest',
        'compromise',
        'total_penalties',
        'total_amount_due',
    ];

    /**
     * Relationship with OrgSetup
     */
    public function organization()
    {
        return $this->belongsTo(OrgSetup::class, 'org_setup_id');
    }

    /**
     * Relationship with WithHolding
     */
    public function withholding()
    {
        return $this->belongsTo(WithHolding::class, 'withholding_id');
    }

    public function atc()
    {
        return $this->belongsTo(Atc::class, 'atc_id');
    }

    /**
     * Calculate Total Penalties
     */
    public function calculateTotalPenalties()
    {
        return ($this->surcharge ?? 0) + ($this->interest ?? 0) + ($this->compromise ?? 0);
    }

    /**
     * Calculate Total Amount Due
     */
    public function calculateTotalAmountDue()
    {
        return ($this->amount_of_remittance ?? 0) - ($this->remitted_previous ?? 0) + $this->calculateTotalPenalties();
    }
}
