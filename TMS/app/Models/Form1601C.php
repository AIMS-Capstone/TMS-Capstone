<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form1601C extends Model
{
    use HasFactory;

    protected $table = '1601C_forms';

    protected $fillable = [
        'filing_period',
        'org_setup_id',
        'atc_id',
        'withholding_id',
        'amended_return',
        'any_taxes_withheld',
        'number_of_sheets',
        'total_compensation',
        'taxable_compensation',
        'tax_due',
        'other_remittances',
        'adjustment',
        'surcharge',
        'interest',
        'compromise',
        'total_amount_due',
        //fillable for 0619E ni re-use ko nalang masyadong mabigat pag nag add ng new migration and model (green coding kuno HAHA)
        'amount_of_remittance',
        'penalties',
        'due_date',
        'remitted_previous',
        //fillable for 1601EQ
        'quarter',
    ];

    /**
     * Relationship with OrgSetup.
     */
    public function organization()
    {
        return $this->belongsTo(OrgSetup::class, 'org_setup_id');
    }

    /**
     * Relationship with ATC.
     */
    public function atc()
    {
        return $this->belongsTo(Atc::class, 'atc_id');
    }

    /**
     * Relationship with Sources (if applicable for computation).
     */
    public function sources()
    {
        return $this->hasMany(Source::class, 'withholding_id', 'id');
    }

    /**
     * Computed Attributes
     */
    public function calculateTotalCompensation()
    {
        return $this->sources()->sum('gross_compensation');
    }

    public function calculateTaxableCompensation()
    {
        return $this->sources()->sum('taxable_compensation');
    }

    public function calculateTaxDue()
    {
        return $this->sources()->sum('tax_due');
    }

    public function calculateTotalAmountDue()
    {
        $taxDue = $this->calculateTaxDue();
        $surcharge = $this->surcharge ?? 0;
        $interest = $this->interest ?? 0;
        $compromise = $this->compromise ?? 0;

        return $taxDue + $surcharge + $interest + $compromise;
    }

    //backend calculation naman ni 0619E
    public function calculateTotalPenalties()
    {
        return $this->surcharge + $this->interest + $this->compromise;
    }

    public function calculateNetRemittance()
    {
        return $this->amount_of_remittance - $this->remitted_previous;
    }

}
