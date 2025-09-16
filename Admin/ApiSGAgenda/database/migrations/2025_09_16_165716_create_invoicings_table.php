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
        Schema::create('invoicings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoicing_code')->index();

            $table->unsignedBigInteger('owner_code');
            $table->foreign('owner_code')->references('owner_code')->on('owners')->onDelete('cascade');

            $table->unsignedBigInteger('attendant_code');
            $table->foreign('attendant_code')->references('attendant_code')->on('attendants')->onDelete('cascade');
            $table->string('attendant', 120);

            $table->unsignedBigInteger('scheduling_code');   
            $table->foreign('scheduling_code')->references('scheduling_code')->on('schedules')->onDelete('cascade');

            $table->float('input_value', 16.2);
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoicings');
    }
};
