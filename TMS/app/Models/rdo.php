<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rdo extends Model
{
    use HasFactory;
    
    protected $fillable = [
      
        'rdo_code',
        'location'
    ];
}
