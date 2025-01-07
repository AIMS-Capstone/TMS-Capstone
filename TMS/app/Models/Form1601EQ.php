<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form1601EQ extends Model
{
    use HasFactory;

    protected $table = '1601eq_forms';

    protected $fillable = [
        'org_setup_id',
        'withholding_id',
        'year',
        'quarter',
        'amended_return',
        'any_taxes_withheld',
        'category',
        'number_of_sheets',
        'total_taxes_withheld',
        'remittances_1st_month', 
        'remittances_2nd_month', 
        'remitted_previous',
        'over_remittance',
        'other_payments', 
        'total_remittances_made', 
        'tax_still_due', 
        'surcharge',
        'interest',
        'compromise',
        'penalties',
        'total_amount_due',
        'created_by',
        'updated_by',
    ];

    /**
     * Relationship with the krazy Form1604E .
     */
    public function annualForm()
    {
        return $this->belongsTo(Form1604E::class, 'withholding_id', 'withholding_id');
    }

    public function atcDetails()
    {
        return $this->hasMany(Form1601EQAtcDetail::class, 'withholding_id', 'withholding_id');
    }
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

    /**
     * Calculate Total Amount Due
     */
    public function calculateTotalAmountDue()
    {
        $taxStillDue = $this->tax_still_due ?? 0;
        $penalties = $this->calculateTotalPenalties();

        return $taxStillDue + $penalties;
    }

    /**
     * Calculate Total Penalties
     */
    public function calculateTotalPenalties()
    {
        $surcharge = $this->surcharge ?? 0;
        $interest = $this->interest ?? 0;
        $compromise = $this->compromise ?? 0;

        return $surcharge + $interest + $compromise;
    }

    /**
     * Calculate Total Remittances
     */
    public function calculateTotalRemittances()
    {
        $remittances1stMonth = $this->remittances_1st_month ?? 0;
        $remittances2ndMonth = $this->remittances_2nd_month ?? 0;
        $remittedPrevious = $this->remitted_previous ?? 0;
        $overRemittance = $this->over_remittance ?? 0;
        $otherPayments = $this->other_payments ?? 0;

        return $remittances1stMonth + $remittances2ndMonth + $remittedPrevious + $overRemittance + $otherPayments;
    }

    /**
     * Get Tax Details for 1601EQ
     */
    public function getTaxDetails()
    {
        return [
            'total_taxes_withheld' => $this->total_taxes_withheld ?? 0,
            'total_remittances_made' => $this->calculateTotalRemittances(),
            'over_remittance' => $this->over_remittance ?? 0,
            'penalties' => $this->calculateTotalPenalties(),
            'total_amount_due' => $this->calculateTotalAmountDue(),
        ];
    }

    /**
     * Scope: Filter by Year and Quarter
     */
    public function scopeForYearAndQuarter($query, $year, $quarter)
    {
        return $query->where('year', $year)
            ->where('quarter', $quarter);
    }

    public function getQuarterText()
    {
        $quarterNames = [
            1 => '1st Quarter (January - March)',
            2 => '2nd Quarter (April - June)',
            3 => '3rd Quarter (July - September)',
            4 => '4th Quarter (October - December)',
        ];

        return $quarterNames[$this->quarter] ?? 'Unknown Quarter';
    }
}
