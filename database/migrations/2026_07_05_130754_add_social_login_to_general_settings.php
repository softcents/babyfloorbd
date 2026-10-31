<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            // Facebook OAuth
            $table->boolean('facebook_login_enabled')->default(false)->after('status');
            $table->string('facebook_app_id')->nullable()->after('facebook_login_enabled');
            $table->string('facebook_app_secret')->nullable()->after('facebook_app_id');
            $table->string('facebook_redirect_url')->nullable()->after('facebook_app_secret');

            // Google OAuth
            $table->boolean('google_login_enabled')->default(false)->after('facebook_redirect_url');
            $table->string('google_client_id')->nullable()->after('google_login_enabled');
            $table->string('google_client_secret')->nullable()->after('google_client_id');
            $table->string('google_redirect_url')->nullable()->after('google_client_secret');
        });
    }

    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn([
                'facebook_login_enabled', 'facebook_app_id', 'facebook_app_secret', 'facebook_redirect_url',
                'google_login_enabled', 'google_client_id', 'google_client_secret', 'google_redirect_url',
            ]);
        });
    }
};
