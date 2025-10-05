<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name');
            $table->text('about_us')->nullable();
            $table->string('lang')->nullable();
            $table->text('google_analeteces')->nullable();
            $table->text('type_description')->nullable();
            $table->text('keywordes')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('url')->nullable();
            $table->boolean('maintenance_mode')->default(false);
            $table->text('facebook_pixel')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
