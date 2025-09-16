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
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('commission_code')->index();

            $table->unsignedBigInteger('owner_code');
            $table->foreign('owner_code')->references('owner_code')->on('owners')->restrictOnDelete();
            
            $table->unsignedBigInteger('attendant_code');   
            $table->foreign('attendant_code')->references('attendant_code')->on('attendants')->onDelete('cascade');
            $table->string('attendant', 120);

            $table->unsignedBigInteger('service_code');
            $table->foreign('service_code')->references('service_code')->on('services')->restrictOnDelete();
            $table->string('service', 120);
            $table->float('service_price', 16.2);

            $table->unsignedBigInteger('category_code')->nullable();
            $table->foreign('category_code')->references('category_code')->on('categories')->restrictOnDelete();
            $table->string('category', 120)->nullable();
            
            $table->unsignedBigInteger('scheduling_code');   
            $table->foreign('scheduling_code')->references('scheduling_code')->on('schedules')->onDelete('cascade');

            $table->float('total_commission', 16.2);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
