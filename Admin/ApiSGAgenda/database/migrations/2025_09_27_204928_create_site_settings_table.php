<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_setting_code')->index();
            $table->unsignedBigInteger('owner_code');
            $table->foreign('owner_code')->references('owner_code')->on('owners')->restrictOnDelete();

            $table->string('theme_color', 100)->default('#ffffff');
            $table->string('site_color', 40)->nullable();
            $table->string('bg_card_color', 40)->nullable();
            $table->string('bg_btn_color', 40)->nullable();
            $table->string('text_color', 40)->nullable();
            $table->string('contact_phone', 20)->nullable();
            $table->string('slogan', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
