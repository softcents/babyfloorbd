<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('features', function (Blueprint $table) {
            $table->increments('id');
            $table->string('icon')->default('fas fa-star');
            $table->string('title');
            $table->string('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        DB::table('features')->insert([
            ['icon' => 'fas fa-leaf',       'title' => 'খাঁটি অর্গানিক আম',  'description' => 'প্রাকৃতিক ও নিরাপদ উপাদান।',        'sort_order' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['icon' => 'fas fa-truck',      'title' => 'দ্রুত ডেলিভারি',      'description' => 'সারা দেশে দ্রুত পৌঁছে যায়।',        'sort_order' => 2, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['icon' => 'fas fa-shield-alt', 'title' => 'নিরাপদ পেমেন্ট',      'description' => '১০০% সুরক্ষিত লেনদেন ব্যবস্থা।',    'sort_order' => 3, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['icon' => 'fas fa-headset',    'title' => 'সহজ সাপোর্ট',         'description' => 'দ্রুত সহায়তা সবসময় প্রস্তুত।',      'sort_order' => 4, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('features');
    }
};
