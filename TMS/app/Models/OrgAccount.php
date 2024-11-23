<?php

namespace App\Models;

use App\Notifications\ClientResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class OrgAccount extends Authenticatable
{
    use Notifiable, HasFactory, SoftDeletes;

    protected $fillable = [
        'org_setup_id',
        'email',
        'password',
        'type',
        'profile_photo_path',
        'deleted_by',
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
      // Relationship to User model for deleted_by
    public function deletedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    // Automatically set deleted_by field on soft delete
    protected static function boot()
    {
        parent::boot();

       static::deleting(function ($orgSetup) {
            if (!$orgSetup->isForceDeleting()) {
                $orgSetup->deleted_by = Auth::user()->id;
                $orgSetup->save();
            }
        });

    }

    //Over-ride the current laravel notification
    public function sendPasswordResetNotification($token)
        {
            $this->notify(new \App\Notifications\ClientResetPasswordNotification($token));
        }
}
