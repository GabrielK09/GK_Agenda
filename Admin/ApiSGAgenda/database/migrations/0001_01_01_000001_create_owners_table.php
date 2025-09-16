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
        Schema::create('owners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_code')->index()->default(1);
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 16)->nullable();
            $table->string('password');
            $table->string('company_name', 160)->nullable();
            $table->string('trade_name', 160)->nullable();

            $table->string('cnpj_cpf', 14)->nullable()->unique();

            $table->string('cep', 8)->nullable();
            $table->string('uf', 2)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('complement', 100)->nullable();
            $table->string('neighborhood', 100)->nullable();
            $table->string('municipality', 100)->nullable();
            $table->string('number', 12)->nullable();
            
            $table->string('pix_key', 8)->nullable()->default('CNPJ/CPF');

            $table->boolean('active', 1)->default(1);
            $table->boolean('completed', 1)->default(0);
            
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owners');
    }
};
