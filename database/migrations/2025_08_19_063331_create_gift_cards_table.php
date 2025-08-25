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
        Schema::create('gift_cards', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique(); // Gift card code
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Who created it
            $table->foreignId('redeemed_by')->nullable()->constrained('users')->onDelete('set null'); // Who redeemed it
            $table->decimal('amount', 15, 2); // Gift card value
            $table->decimal('balance', 15, 2); // Remaining balance
            $table->string('status')->default('active'); // active, redeemed, expired, cancelled
            $table->text('message')->nullable(); // Optional message from sender
            $table->timestamp('expires_at')->nullable(); // Expiry date
            $table->timestamp('redeemed_at')->nullable(); // When it was redeemed
            $table->timestamps();

            $table->index(['code', 'status']);
            $table->index(['created_by', 'status']);
            $table->index('redeemed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_cards');
    }
};
