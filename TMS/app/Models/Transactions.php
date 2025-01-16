<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Transactions extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['transaction_type', 'date', 'inv_number', 'reference', 'total_amount', 'contact', 'itr_include', 'total_amount_credit', 'total_amount_debit', 'vatable_sales','vatable_purchase', 'vat_amount', 'non_vatable_sales','non_vatable_purchase','status', 'QAP', 'organization_id', 'deleted_by', 'withholding_id', ];

    public function taxRows()
    {
        return $this->hasMany(TaxRow::class, 'transaction_id');
    }
    public function contactDetails()
    {
        return $this->belongsTo(Contacts::class, 'contact');
    }
 
    public function taxReturns()
    {
        return $this->belongsToMany(TaxReturn::class, 'tax_return_transactions', 'transaction_id', 'tax_return_id')
            ->withPivot('allocation_percentage') // Access allocation percentage
            ->withTimestamps();
    }
    public function taxReturnTransactions()
    {
        return $this->hasMany(TaxReturnTransaction::class, 'transaction_id');
    }

    // Organization associated with the transaction.
    public function organization()
    {
        return $this->belongsTo(OrgSetup::class, 'organization_id');
    }

    // User who soft-deleted the transaction.
    public function deletedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    // Withholding details for the transaction.
    public function withholding()
    {
        return $this->belongsTo(WithHolding::class, 'withholding_id');
    }

    /**
     * Boot method to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically set the deleted_by field on soft delete.
        static::deleting(function ($transaction) {
            if (!$transaction->isForceDeleting()) {
                $transaction->deleted_by = Auth::user()->id ?? null; // Set user ID or null if not authenticated
                $transaction->save();
            }
        });
    }
}
