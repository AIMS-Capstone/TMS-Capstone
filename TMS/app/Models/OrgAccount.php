<?php

namespace App\Models;

use App\Notifications\ClientResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class OrgAccount extends Authenticatable
{
    use Notifiable, HasFactory, SoftDeletes;

    protected $fillable = [
        'org_setup_id',
        'email',
        'password',
        'profile_photo_path',
    ];

    // Relationship with OrgSetup
    public function orgSetup()
    {
        return $this->belongsTo(OrgSetup::class, 'org_setup_id');
    }

    // Example relationships with other models
    public function taxReturns()
    {
        return $this->hasMany(TaxReturn::class, 'organization_id', 'org_setup_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transactions::class, 'organization_id', 'org_setup_id');
    }
    public function parseUserAgent($userAgentString = null)
    {
        if (!$userAgentString) {
            $userAgentString = $_SERVER['HTTP_USER_AGENT'] ?? '';
        }

        $browserInfo = get_browser($userAgentString, true);

        return [
            'platform' => $browserInfo['platform'] ?? 'Unknown',
            'browser' => $browserInfo['browser'] ?? 'Unknown',
            'version' => $browserInfo['version'] ?? 'Unknown'
        ];
    }
}
