@extends('backEnd.layouts.master')
@section('title','Social Login Settings')

@section('css')
<style>
    .sl-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 24px rgba(0,0,0,.06);
        border: 1px solid #e8edf3;
        margin-bottom: 28px;
        overflow: hidden;
    }
    .sl-card-head {
        padding: 18px 24px;
        border-bottom: 1px solid #f0f4f8;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .sl-card-head .icon-wrap {
        width: 40px; height: 40px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px;
    }
    .sl-card-head .icon-wrap.fb { background: #e7f0fd; color: #1877f2; }
    .sl-card-head .icon-wrap.gg { background: #fce8e6; color: #ea4335; }
    .sl-card-head h5 { margin: 0; font-size: 15px; font-weight: 700; color: #1e293b; }
    .sl-card-head p  { margin: 0; font-size: 12px; color: #64748b; }
    .sl-card-body { padding: 24px; }
    .sl-form-group { margin-bottom: 18px; }
    .sl-form-group label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
    .sl-form-group input[type="text"], .sl-form-group input[type="url"] {
        width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px;
        font-size: 13px; color: #374151; background: #f9fafb;
        transition: border-color .2s, box-shadow .2s;
    }
    .sl-form-group input:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.15); background: #fff; }
    .sl-switch-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding: 14px 16px; background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; }
    .sl-switch-row span { font-size: 14px; font-weight: 600; color: #374151; }
    .sl-toggle { position: relative; width: 44px; height: 24px; }
    .sl-toggle input { opacity: 0; width: 0; height: 0; }
    .sl-toggle .slider { position: absolute; cursor: pointer; inset: 0; background: #d1d5db; border-radius: 24px; transition: .3s; }
    .sl-toggle .slider:before { content: ''; position: absolute; width: 18px; height: 18px; left: 3px; bottom: 3px; background: #fff; border-radius: 50%; transition: .3s; box-shadow: 0 1px 3px rgba(0,0,0,.2); }
    .sl-toggle input:checked + .slider { background: #22c55e; }
    .sl-toggle input:checked + .slider:before { transform: translateX(20px); }
    .sl-help { font-size: 11px; color: #94a3b8; margin-top: 4px; }
    .sl-btn-save { background: linear-gradient(135deg,#6366f1,#4f46e5); color: #fff; border: none; padding: 11px 28px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: opacity .2s; }
    .sl-btn-save:hover { opacity: .9; }
    .sl-url-hint { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; font-size: 12px; color: #166534; }
    .sl-url-hint code { background: #dcfce7; padding: 2px 6px; border-radius: 4px; font-family: monospace; word-break: break-all; }
    .page-title-box { padding: 20px 0 10px; }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="page-title-box">
            <h4 class="page-title">Social Login Settings</h4>
        </div>

        <form action="{{ route('admin.social_login.update') }}" method="POST">
            @csrf

            {{-- Facebook --}}
            <div class="sl-card">
                <div class="sl-card-head">
                    <div class="icon-wrap fb"><i class="fab fa-facebook-f"></i></div>
                    <div>
                        <h5>Facebook Login</h5>
                        <p>Facebook OAuth App দিয়ে customer login</p>
                    </div>
                </div>
                <div class="sl-card-body">
                    <div class="sl-switch-row">
                        <span><i class="fas fa-power-off" style="margin-right:6px;color:#1877f2;"></i> Facebook Login সক্রিয় করুন</span>
                        <label class="sl-toggle">
                            <input type="checkbox" name="facebook_login_enabled" value="1" {{ old('facebook_login_enabled', $setting->facebook_login_enabled ?? false) ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="sl-url-hint">
                        <strong>Callback URL (Redirect URI):</strong><br>
                        <code>{{ url('/customer/auth/facebook/callback') }}</code><br>
                        <span style="margin-top:4px;display:block;">Facebook Developers Console → App → Facebook Login → Settings → Valid OAuth Redirect URIs এ এই URL টি যোগ করুন।</span>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="sl-form-group">
                                <label><i class="fas fa-key" style="margin-right:5px;color:#1877f2;"></i> App ID (Client ID)</label>
                                <input type="text" name="facebook_app_id" value="{{ old('facebook_app_id', $setting->facebook_app_id ?? '') }}" placeholder="1234567890123456">
                                <div class="sl-help">Facebook Developers → Your App → Settings → Basic → App ID। এটি সাইটের <code>fb:app_id</code> মেটা ট্যাগ ও Facebook Debugger-এর জন্যও ব্যবহার হয়।</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="sl-form-group">
                                <label><i class="fas fa-lock" style="margin-right:5px;color:#1877f2;"></i> App Secret</label>
                                <input type="text" name="facebook_app_secret" value="{{ old('facebook_app_secret', $setting->facebook_app_secret ?? '') }}" placeholder="abcdef1234567890abcdef1234567890">
                                <div class="sl-help">Facebook Developers → Your App → Settings → Basic → App Secret</div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="sl-form-group">
                                <label><i class="fas fa-link" style="margin-right:5px;color:#1877f2;"></i> Redirect URL</label>
                                <input type="url" name="facebook_redirect_url" value="{{ old('facebook_redirect_url', $setting->facebook_redirect_url ?? url('/customer/auth/facebook/callback')) }}" placeholder="{{ url('/customer/auth/facebook/callback') }}">
                                <div class="sl-help">উপরে দেওয়া Callback URL টি এখানে পেস্ট করুন।</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Google --}}
            <div class="sl-card">
                <div class="sl-card-head">
                    <div class="icon-wrap gg"><i class="fab fa-google"></i></div>
                    <div>
                        <h5>Google Login</h5>
                        <p>Google OAuth 2.0 দিয়ে customer login</p>
                    </div>
                </div>
                <div class="sl-card-body">
                    <div class="sl-switch-row">
                        <span><i class="fas fa-power-off" style="margin-right:6px;color:#ea4335;"></i> Google Login সক্রিয় করুন</span>
                        <label class="sl-toggle">
                            <input type="checkbox" name="google_login_enabled" value="1" {{ old('google_login_enabled', $setting->google_login_enabled ?? false) ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="sl-url-hint" style="background:#fff8f0;border-color:#fed7aa;">
                        <strong>Callback URL (Authorized Redirect URI):</strong><br>
                        <code>{{ url('/customer/auth/google/callback') }}</code><br>
                        <span style="margin-top:4px;display:block;">Google Cloud Console → Credentials → OAuth 2.0 Client → Authorized redirect URIs এ এই URL টি যোগ করুন।</span>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="sl-form-group">
                                <label><i class="fas fa-key" style="margin-right:5px;color:#ea4335;"></i> Client ID</label>
                                <input type="text" name="google_client_id" value="{{ old('google_client_id', $setting->google_client_id ?? '') }}" placeholder="1234567890-abc.apps.googleusercontent.com">
                                <div class="sl-help">Google Cloud Console → Credentials → OAuth 2.0 Client IDs → Client ID</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="sl-form-group">
                                <label><i class="fas fa-lock" style="margin-right:5px;color:#ea4335;"></i> Client Secret</label>
                                <input type="text" name="google_client_secret" value="{{ old('google_client_secret', $setting->google_client_secret ?? '') }}" placeholder="GOCSPX-xxxxxxxxxxxxxxxxxxxx">
                                <div class="sl-help">Google Cloud Console → Credentials → OAuth 2.0 Client IDs → Client Secret</div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="sl-form-group">
                                <label><i class="fas fa-link" style="margin-right:5px;color:#ea4335;"></i> Redirect URL</label>
                                <input type="url" name="google_redirect_url" value="{{ old('google_redirect_url', $setting->google_redirect_url ?? url('/customer/auth/google/callback')) }}" placeholder="{{ url('/customer/auth/google/callback') }}">
                                <div class="sl-help">উপরে দেওয়া Callback URL টি এখানে পেস্ট করুন।</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="text-align:right;">
                <button type="submit" class="sl-btn-save"><i class="fas fa-save" style="margin-right:6px;"></i> Settings Save করুন</button>
            </div>
        </form>

    </div>
</div>
@endsection
