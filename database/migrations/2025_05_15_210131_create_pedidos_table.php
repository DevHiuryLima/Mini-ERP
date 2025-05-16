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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique()->nullable();
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->decimal('frete', 8, 2)->default(0.00);
            $table->decimal('total', 10, 2)->default(0.00);
            $table->unsignedBigInteger('cupom_id')->nullable();
            $table->string('cep')->nullable();
            $table->string('endereco')->nullable();
            $table->string('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('uf')->nullable();
            $table->string('email_cliente')->nullable();
            $table->enum('status', ['pendente', 'processando', 'enviado', 'entregue', 'cancelado'])->default('pendente');
            $table->timestamps();

            $table->foreign('cupom_id')->references('id')->on('cupons')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
