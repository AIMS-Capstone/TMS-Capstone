<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coa extends Model
{
    use HasFactory;

    protected $table = 'coas';

    protected $fillable = [
        'type',
        'sub_type',
        'code',
        'name',
        'description',
        'organization_id', 
        'status',
    ];

    /**
     * Relationship with Organization
     */
    public function organization()
    {
        return $this->belongsTo(OrgSetup::class, 'organization_id');
    }
}
