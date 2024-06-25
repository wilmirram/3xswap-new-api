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
        Schema::create('exchange_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('wallet_address');
            $table->string('token_from');
            $table->string('token_to');
            $table->double('amount_from');
            $table->double('amount_to');
            $table->double('price');
            $table->unsignedInteger('chain_id');
            $table->string('transaction_hash');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_transactions');
    }
};
