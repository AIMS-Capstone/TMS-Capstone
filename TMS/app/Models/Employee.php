<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'date_of_birth',
        'tin',
        'nationality',
        'contact_number',
        'organization_id',
        'deleted_by',
    ];

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function employments()
    {
        return $this->hasMany(Employment::class);
        
    }

    public function transactions()
    {
        return $this->hasMany(Transactions::class, 'contact'); 
    }

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
