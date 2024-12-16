<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Contacts extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contact_type',
        'bus_name',
        'classification',
        'contact_email',
        'contact_phone',
        'contact_tin',
        'revenue_tax_type',
        'revenue_atc',
        'revenue_chart_accounts',
        'expense_tax_type',
        'expense_atc',
        'expense_chart_accounts',
        'contact_address',
        'contact_city',
        'contact_zip',
        'organization_id', 
        'deleted_by',
    ];

    /**
     * Relationship with Transactions (if needed)
     */
    public function transactions()
    {
        return $this->hasMany(Transactions::class, 'contact'); // Adjust 'contact_id' if different
    }

    /**
     * Relationship with Organization (if applicable)
     */
    public function organization()
    {
        return $this->belongsTo(OrgSetup::class, 'organization_id');
    }

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
