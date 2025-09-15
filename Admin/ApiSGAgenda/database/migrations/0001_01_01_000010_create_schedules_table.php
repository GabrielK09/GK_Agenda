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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scheduling_code')->index();

            $table->unsignedBigInteger('owner_code');
            $table->foreign('owner_code')->references('owner_code')->on('owners')->onDelete('cascade');

            $table->unsignedBigInteger('attendant_code');
            $table->foreign('attendant_code')->references('attendant_code')->on('attendants')->onDelete('cascade');
            $table->string('attendant', 120);

            $table->unsignedBigInteger('service_code');
            $table->foreign('service_code')->references('service_code')->on('services')->restrictOnDelete();
            $table->string('service', 120);

            $table->string('customer_name', 120);
            $table->string('customer_phone', 120);

            $table->string('day', 12);
            $table->string('hour', 12);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
