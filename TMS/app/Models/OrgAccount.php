<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrgAccount extends Model
{
    use HasFactory;
    protected $fillable = [
        'org_setup_id', 
        'email',
        'password',
    ];

    // Define the relationship with OrgSetup
    public function orgSetup()
    {
        return $this->belongsTo(OrgSetup::class, 'org_setup_id');
    }
}
