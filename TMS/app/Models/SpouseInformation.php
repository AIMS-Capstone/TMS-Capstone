<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpouseInformation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'spouse_information';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'individual_background_information_id',
        'employment_status',
        'tin',
        'rdo',
        'last_name',
        'first_name',
        'middle_name',
        'filer_type',
        'alphanumeric_tax_code',  
        'citizenship',            
        'foreign_tax_number',     
        'claiming_foreign_credits',
    ];

    /**
     * Get the individual background information associated with this spouse.
     */
    public function individualBackgroundInformation()
    {
        return $this->belongsTo(IndividualBackgroundInformation::class, 'individual_background_information_id');
    }
    public function spouseTransactions()
    {
        return $this->hasMany(SpouseTransaction::class);
    }
    public function taxOptionRate()
    {
        return $this->hasOne(SpouseTaxOptionRate::class, 'spouse_information_id');
    }
    

    /**
     * Concatenate the spouse's full name in "Last Name, First Name Middle Name" format.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->last_name}, {$this->first_name} {$this->middle_name}";
    }
}
