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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('hash', 255)->nullable();
            $table->string('client_name', 255);
            $table->string('client_cpf', 11);
            $table->bigInteger('store_id')->unsigned();
            $table->integer('ticket');
            $table->string('lock_id', 255)->nullable()->default(null);
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->integer('installments')->default(1);
            $table->string('transaction_id', 255)->nullable();
            $table->string('payment_type', 255)->nullable();
            $table->string('charge_id', 255)->nullable(); //paid_at
            $table->string('status', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
