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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_code')->index();
            $table->unsignedBigInteger('owner_code');
            $table->foreign('owner_code')->references('owner_code')->on('owners')->restrictOnDelete();

            $table->unsignedBigInteger('category_code')->nullable();
            $table->foreign('category_code')->references('category_code')->on('categories')->restrictOnDelete();
            $table->string('category', 120)->nullable();

            $table->string('name', 120); 
            $table->float('price', 16.2);
            $table->text('description');
            $table->string('duration', 120);
            $table->boolean('is_home_service', 1)->default(0)->nullable();
            $table->boolean('check_availability', 1)->default(0)->nullable();
            $table->boolean('active', 1)->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
