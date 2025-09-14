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
        Schema::create('attendant_hours', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attendant_hour_code')->index();

            $table->unsignedBigInteger('owner_code');
            $table->foreign('owner_code')->references('owner_code')->on('owners')->restrictOnDelete();
            
            $table->unsignedBigInteger('attendant_code');   
            $table->foreign('attendant_code')->references('attendant_code')->on('attendants')->onDelete('cascade');
            $table->string('attendant', 120);

            $table->string('day', 12)->nullable()->default('');
            $table->string('time', 12)->nullable()->default('');
            $table->string('interval', 12)->nullable()->default('');
            $table->string('interval_between_services', 12)->nullable()->default('');
            $table->boolean('marked_day', 12)->nullable()->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendant_hours');
    }
};
