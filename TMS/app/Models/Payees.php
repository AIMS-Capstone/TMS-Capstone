<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payees extends Model
{
    use HasFactory;

    protected $table = 'payees';

    protected $fillable = [
        'organization_id',
        'withholding_id',
        'atc_id',
        'contact_id',
        'amount',
    ];

    public function organization()
    {
        return $this->belongsTo(OrgSetup::class, 'organization_id');
    }

    public function withholding()
    {
        return $this->belongsTo(WithHolding::class, 'withholding_id');
    }

    public function atc()
    {
        return $this->belongsTo(Atc::class, 'atc_id');
    }

    public function contact()
    {
        return $this->belongsTo(Contacts::class, 'contact_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transactions::class, 'contact', 'contact_id');
    }
}
