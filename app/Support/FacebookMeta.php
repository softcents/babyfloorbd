<?php

namespace App\Support;

use App\Models\AdsAnalyticsSetting;
use App\Models\GeneralSetting;

class FacebookMeta
{
    public static function appId(?GeneralSetting $setting = null): ?string
    {
        $candidates = [
            optional($setting)->facebook_app_id,
            optional(GeneralSetting::where('status', 1)->first())->facebook_app_id,
            config('services.facebook.app_id'),
            optional(AdsAnalyticsSetting::where('platform', 'facebook')->first())->app_id,
        ];

        foreach ($candidates as $id) {
            $id = trim((string) $id);
            if ($id !== '' && ctype_digit($id)) {
                return $id;
            }
        }

        return null;
    }
}
