<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class TaxReturn extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'title',
        'year',
        'month',
        'created_by',      
        'organization_id',     
        'status',
        'deleted_by',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by'); 
    }
    public function transactions()
    {
        return $this->belongsToMany(Transactions::class, 'tax_return_transactions', 'tax_return_id', 'transaction_id')
                    ->withPivot('allocation_percentage') // If you want to access the allocation percentage
                    ->withTimestamps();
    }
    public function taxReturnTransactions()
    {
        return $this->hasMany(TaxReturnTransaction::class, 'tax_return_id');
    }

    //client's page purposes
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->whereDate('created_at', '>', today());
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }

    public function getFormattedPeriodAttribute()
    {
        if ($this->title === '2550Q') {
            switch ($this->month) {
                case 1:
                case 2:
                case 3:
                    return "1st Quarter (Jan 1 - Mar 31)";
                case 4:
                case 5:
                case 6:
                    return "2nd Quarter (Apr 1 - Jun 30)";
                case 7:
                case 8:
                case 9:
                    return "3rd Quarter (Jul 1 - Sep 30)";
                case 10:
                case 11:
                case 12:
                    return "4th Quarter (Oct 1 - Dec 31)";
            }
        } elseif ($this->title === '2550M') {
            $month = is_numeric($this->month) ? $this->month : date('n', strtotime($this->month . ' 1'));

            return \Carbon\Carbon::createFromDate($this->year, $month)->format('F Y');
        }

        return $this->year;
    }

    //Organization name fetch
    public function Organization()
    {
        return $this->belongsTo(Orgsetup::class, 'organization_id');
    }

      public function deletedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
    public function individualBackgroundInformation()
    {
        return $this->hasOne(IndividualBackgroundInformation::class);
    }
    public function individualTransaction()
    {
        return $this->belongsToMany(Transactions::class, 'individual_transactions', 'tax_return_id', 'transaction_id')
                    ->withPivot('amount', 'description')
                    ->withTimestamps();
    }
    
    public function spouseTransactions()
    {
        return $this->belongsToMany(Transactions::class, 'spouse_transactions', 'tax_return_id', 'transaction_id')
                    ->withPivot('amount', 'description')
                    ->withTimestamps();
    }
    
    
     protected static function boot()
    {
        parent::boot();

       static::deleting(function ($trasaction) {
            if (!$$trasaction->isForceDeleting()) {
                $trasaction->deleted_by = Auth::user()->id;
                $trasaction->save();
            }
        });

    }


}
