<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class SocialLoginSettingController extends Controller
{
    public function edit()
    {
        $setting = GeneralSetting::first();
        return view('backEnd.settings.social_login', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'facebook_app_id'     => 'nullable|string|max:255',
            'facebook_app_secret' => 'nullable|string|max:255',
            'facebook_redirect_url' => 'nullable|url|max:500',
            'google_client_id'    => 'nullable|string|max:255',
            'google_client_secret'=> 'nullable|string|max:255',
            'google_redirect_url' => 'nullable|url|max:500',
        ]);

        $setting = GeneralSetting::first();
        if (!$setting) {
            Toastr::error('Settings not found.', 'Error');
            return back();
        }

        $setting->update([
            'facebook_login_enabled' => $request->boolean('facebook_login_enabled'),
            'facebook_app_id'        => $request->input('facebook_app_id'),
            'facebook_app_secret'    => $request->input('facebook_app_secret'),
            'facebook_redirect_url'  => $request->input('facebook_redirect_url'),

            'google_login_enabled'   => $request->boolean('google_login_enabled'),
            'google_client_id'       => $request->input('google_client_id'),
            'google_client_secret'   => $request->input('google_client_secret'),
            'google_redirect_url'    => $request->input('google_redirect_url'),
        ]);

        Toastr::success('Social login settings saved successfully.', 'Success');
        return back();
    }
}
