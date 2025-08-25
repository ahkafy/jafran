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
        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->string('method')->default('eft'); // Only EFT for now

            // Bank Account Information (US Standard)
            $table->string('bank_name');
            $table->string('account_holder_name');
            $table->string('account_number');
            $table->string('routing_number'); // ABA routing number
            $table->string('swift_code')->nullable(); // For international transfers
            $table->string('bank_address');
            $table->string('bank_city');
            $table->string('bank_state');
            $table->string('bank_zip_code');
            $table->string('account_type')->default('checking'); // checking, savings

            $table->string('status')->default('pending'); // pending, approved, processed, rejected
            $table->text('notes')->nullable(); // User notes
            $table->text('admin_notes')->nullable(); // Admin notes
            $table->string('reference_number')->nullable(); // Bank reference number
            $table->timestamp('processed_at')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawal_requests');
    }
};
