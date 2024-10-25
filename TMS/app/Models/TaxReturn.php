<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxReturn extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'year',
        'month',
        'created_by',      
        'organization_id',     
        'status',
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
}
