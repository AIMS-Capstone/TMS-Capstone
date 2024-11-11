<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class OrgSetup extends Model
{
    use HasFactory, SoftDeletes;

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
        'financial_year_end',
        'deleted_by',
    ];

    // Relationship to OrgAccount
    public function account()
    {
        return $this->hasOne(OrgAccount::class, 'org_setup_id', 'id');
    }

    // Relationship to RDO
    public function rdo()
    {
        return $this->belongsTo(RDO::class, 'rdo');
    }

    // Relationship to User model for deleted_by
    public function deletedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    // Automatically set deleted_by field on soft delete
    protected static function boot()
    {
        parent::boot();

       static::deleting(function ($orgSetup) {
            if (!$orgSetup->isForceDeleting()) {
                $orgSetup->deleted_by = Auth::user()->id;
                $orgSetup->save();
            }
        });

    }
}
