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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_code');
            $table->foreign('owner_code')->references('owner_code')->on('owners')->restrictOnDelete();
            $table->string('company_name', 160);
            $table->string('trade_name', 160);
            $table->string('cnpj_cpf', 14);
            $table->string('phone', 14);
            $table->string('mail', 120);
            $table->string('date_of_birth', 40);
            $table->string('cep', 8)->nullable();
            $table->string('uf', 2)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('complement', 100)->nullable();
            $table->string('neighborhood', 100)->nullable();
            $table->string('municipality', 100)->nullable();
            $table->string('number', 12)->nullable();

            $table->string('sex', 5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
