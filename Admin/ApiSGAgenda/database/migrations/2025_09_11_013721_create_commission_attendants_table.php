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
        Schema::create('commission_attendants', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('owner_code');
            $table->foreign('owner_code')->references('owner_code')->on('owners')->restrictOnDelete();

            $table->unsignedBigInteger('attendant_code');
            $table->foreign('attendant_code')->references('attendant_code')->on('attendants')->restrictOnDelete();
            $table->string('attendant_name', 120); 
            
            $table->unsignedBigInteger('service_code')->nullable();
            $table->foreign('service_code')->references('service_code')->on('services')->restrictOnDelete();
            $table->string('service_name', 120); 

            $table->unsignedBigInteger('category_code')->nullable();
            $table->foreign('category_code')->references('category_code')->on('categories')->restrictOnDelete();
            $table->string('category_name', 120); 

            $table->float('perc_commission', 16.2)->nullable();
            $table->float('fixed_commission', 16.2)->nullable();    
            
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_attendants');
    }
};
