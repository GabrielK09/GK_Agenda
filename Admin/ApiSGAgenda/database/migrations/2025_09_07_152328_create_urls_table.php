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
        Schema::create('urls', function (Blueprint $table) {
            // O prefixo do site: https://app.gkagenda.com.br/ nome a ser inserido
            // No caso iria ficar http://localhost ou ip:9000/app.gkagenda/nome a ser inserido

            $table->id();
            $table->unsignedBigInteger('owner_code');
            $table->foreign('owner_code')->references('owner_code')->on('owners')->restrictOnDelete();
            $table->string('site_name')->unique();
            $table->string('site_url')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('urls');
    }
};
