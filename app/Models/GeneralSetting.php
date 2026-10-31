<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'facebook_login_enabled' => 'boolean',
        'google_login_enabled'   => 'boolean',
    ];

    public function getFacebookConfig(): array
    {
        return [
            'client_id'     => $this->facebook_app_id,
            'client_secret' => $this->facebook_app_secret,
            'redirect'      => $this->facebook_redirect_url,
        ];
    }

    public function getGoogleConfig(): array
    {
        return [
            'client_id'     => $this->google_client_id,
            'client_secret' => $this->google_client_secret,
            'redirect'      => $this->google_redirect_url,
        ];
    }
}
