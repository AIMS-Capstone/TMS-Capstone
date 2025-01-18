<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WithHolding extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'withholdings';

    protected $fillable = [
        'type',
        'title',
        'employee_id',
        'employment_id',
        'organization_id',
        'month',
        'quarter',
        'year',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Relationship with Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Relationship with Employment
    public function employment()
    {
        return $this->belongsTo(Employment::class);
    }

    //Relationship with Organization
    public function organization()
    {
        return $this->belongsTo(OrgSetup::class);
    }

    // Relationship with User (Creator)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relationship with User (Updater)
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    

    // Relationship with Sources
    public function sources()
    {
        return $this->hasMany(Source::class, 'withholding_id');
    }

    // Dynamic calculated attributes (optional)
    public function getGrossCompensationAttribute()
    {
        return $this->sources->sum('gross_compensation');
    }

    public function getTaxableCompensationAttribute()
    {
        return $this->sources->sum('taxable_compensation');
    }

    public function getTaxDueAttribute()
    {
        return $this->sources->sum('tax_due');
    }

    public function deletedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    // Automatically set deleted_by field on soft delete
    protected static function boot()
    {
        parent::boot();

       static::deleting(function ($withHolding) {
            if (!$withHolding->isForceDeleting()) {
                $withHolding->deleted_by = Auth::id();
                $withHolding->save();
            }
        });

    }
}
