<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    use HasFactory;
    protected $fillable = [
        'contact_role',
        'contact_type',
        'bus_name',
        'contact_tin',
        'contact_address',
        'contact_city',
        'contact_zip',
    ];
    public function transactions()
    {
        return $this->hasMany(Transactions::class, 'contact'); // Adjust 'contact_id' if different
    }
}
