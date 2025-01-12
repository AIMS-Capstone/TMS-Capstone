<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgAccountSession extends Model
{
    protected $table = 'org_account_sessions';

    protected $fillable = [
        'id',
        'org_account_id',
        'ip_address',
        'user_agent',
        'payload',
        'last_activity',
    ];

    public $timestamps = false; // Disable default timestamps if not needed
}
