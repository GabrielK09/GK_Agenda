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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_code')->index();
            $table->unsignedBigInteger('owner_code');
            $table->foreign('owner_code')->references('owner_code')->on('owners')->restrictOnDelete();
            $table->string('name');
            $table->float('price', 16.2);
            $table->float('amount', 16.2);
            $table->boolean('active', 1)->default(1);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
