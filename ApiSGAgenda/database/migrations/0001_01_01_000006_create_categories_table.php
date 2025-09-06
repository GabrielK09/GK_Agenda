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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_code')->index();
            $table->unsignedBigInteger('owner_code');            
            $table->foreign('owner_code')->references('owner_code')->on('owners')->cascadeOnDelete();

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('categories', column: 'id');

            $table->string('name', 120); 
            $table->text('description');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
