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
        Schema::table('withdrawal_requests', function (Blueprint $table) {
            // Update method column to support multiple withdrawal methods
            $table->string('method')->default('bank')->change(); // bank, mbook

            // Add processing fee fields
            $table->decimal('processing_fee_percentage', 5, 2)->default(0);
            $table->decimal('processing_fee_amount', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2)->default(0); // amount after fees

            // Add MBook specific fields
            $table->string('mbook_name')->nullable();
            $table->string('mbook_country')->nullable();
            $table->string('mbook_currency')->nullable();
            $table->string('mbook_wallet_id')->nullable();

            // Add bank country for fee calculation
            $table->string('bank_country')->nullable()->after('bank_zip_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('withdrawal_requests', function (Blueprint $table) {
            $table->dropColumn([
                'processing_fee_percentage',
                'processing_fee_amount',
                'net_amount',
                'mbook_name',
                'mbook_country',
                'mbook_currency',
                'mbook_wallet_id',
                'bank_country'
            ]);

            // Reset method column back to original
            $table->string('method')->default('eft')->change();
        });
    }
};
