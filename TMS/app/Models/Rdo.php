<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rdo extends Model 
{
    use HasFactory;

    protected $table = 'rdos'; // Ensure this is the correct table name
    
    protected $fillable = [
        'rdo_code',
        'location'
    ];
}
