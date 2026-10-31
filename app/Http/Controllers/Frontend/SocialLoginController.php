<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\GeneralSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    /**
     * DB থেকে Socialite config লোড করে driver তৈরি করে।
     */
    private function getSocialiteDriver(string $provider)
    {
        $setting = GeneralSetting::first();

        if ($provider === 'facebook') {
            config([
                'services.facebook.client_id'     => $setting->facebook_app_id,
                'services.facebook.client_secret'  => $setting->facebook_app_secret,
                'services.facebook.redirect'       => $setting->facebook_redirect_url,
            ]);
        } elseif ($provider === 'google') {
            config([
                'services.google.client_id'     => $setting->google_client_id,
                'services.google.client_secret'  => $setting->google_client_secret,
                'services.google.redirect'       => $setting->google_redirect_url,
            ]);
        }

        return Socialite::driver($provider);
    }

    /**
     * সোশ্যাল প্রোভাইডারে redirect করো।
     */
    public function redirect(string $provider)
    {
        $setting = GeneralSetting::first();

        if ($provider === 'facebook' && !($setting->facebook_login_enabled ?? false)) {
            Toastr::error('Facebook login সক্রিয় নেই।', 'Error');
            return redirect()->route('customer.login');
        }
        if ($provider === 'google' && !($setting->google_login_enabled ?? false)) {
            Toastr::error('Google login সক্রিয় নেই।', 'Error');
            return redirect()->route('customer.login');
        }

        return $this->getSocialiteDriver($provider)->redirect();
    }

    /**
     * Callback: provider থেকে user তথ্য নিয়ে login/register করো।
     */
    public function callback(string $provider)
    {
        try {
            $socialUser = $this->getSocialiteDriver($provider)->user();
        } catch (\Exception $e) {
            Toastr::error('সোশ্যাল লগইন ব্যর্থ হয়েছে। আবার চেষ্টা করুন।', 'Error');
            return redirect()->route('customer.login');
        }

        $email    = $socialUser->getEmail();
        $name     = $socialUser->getName();
        $socialId = $socialUser->getId();
        $avatar   = $socialUser->getAvatar();

        // Customer খুঁজে বের করো বা তৈরি করো
        $customer = null;

        if ($email) {
            $customer = Customer::where('email', $email)->first();
        }

        if (!$customer) {
            $customer = Customer::where('social_provider', $provider)
                ->where('social_id', $socialId)
                ->first();
        }

        if (!$customer) {
            // নতুন customer তৈরি করো
            $customer = Customer::create([
                'name'            => $name ?? 'User',
                'email'           => $email,
                'phone'           => null,
                'password'        => Hash::make(Str::random(24)),
                'social_provider' => $provider,
                'social_id'       => $socialId,
                'avatar'          => $avatar,
                'status'          => 1,
            ]);
        } else {
            // সোশ্যাল info আপডেট করো
            $customer->update([
                'social_provider' => $provider,
                'social_id'       => $socialId,
                'avatar'          => $avatar ?: $customer->avatar,
            ]);
        }

        if (!$customer->status) {
            Toastr::error('আপনার অ্যাকাউন্ট নিষ্ক্রিয় করা হয়েছে।', 'Error');
            return redirect()->route('customer.login');
        }

        Auth::guard('customer')->login($customer, true);
        Toastr::success('সফলভাবে লগইন হয়েছেন।', 'Success');
        return redirect()->intended(route('customer.account'));
    }
}
