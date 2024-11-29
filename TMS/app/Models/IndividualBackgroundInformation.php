<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndividualBackgroundInformation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'individual_background_information';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tax_return_id',
        'date_of_birth',
        'filer_type',
        'alphanumeric_tax_code',
        'civil_status',
        'citizenship',
        'foreign_tax',
        'claiming_foreign_credits',
    ];

    /**
     * Get the tax return that owns this background information.
     */
    public function taxReturn()
    {
        return $this->belongsTo(TaxReturn::class);
    }
    public function individualTransaction()
    {
        return $this->hasMany(IndividualTransaction::class);
    }

    /**
     * Get the spouse information associated with this individual.
     */
    public function spouseInformation()
    {
        return $this->hasOne(SpouseInformation::class, 'individual_background_information_id');
    }
}
