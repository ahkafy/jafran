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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // credit, debit
            $table->string('category'); // deposit, withdrawal, investment, return, commission, gift_card, transfer
            $table->decimal('amount', 15, 2);
            $table->string('payment_method')->nullable(); // stripe, paypal, bank_transfer, gift_card
            $table->string('payment_reference')->nullable(); // transaction ID from payment gateway
            $table->text('description');
            $table->string('status')->default('pending'); // pending, completed, failed, cancelled
            $table->json('metadata')->nullable(); // additional data like payment gateway response
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'type']);
            $table->index(['user_id', 'status']);
            $table->index(['category', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
