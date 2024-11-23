<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'zip_code',
        'region',
        'addressable_id',
        'addressable_type',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'address_id', 'id');
    }

    public function addressable()
    {
        return $this->morphTo();
    }

}
