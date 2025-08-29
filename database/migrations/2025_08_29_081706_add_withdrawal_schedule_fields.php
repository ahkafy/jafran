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
            // Add fields for bi-monthly processing schedule
            $table->enum('balance_type', ['commission', 'returns', 'both'])->default('both')->after('amount');
            $table->decimal('commission_amount', 15, 2)->default(0)->after('balance_type');
            $table->decimal('returns_amount', 15, 2)->default(0)->after('commission_amount');
            $table->date('scheduled_processing_date')->nullable()->after('returns_amount');
            $table->enum('processing_cycle', ['mid_month', 'month_end'])->nullable()->after('scheduled_processing_date');
            $table->json('balance_breakdown')->nullable()->after('processing_cycle');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('withdrawal_requests', function (Blueprint $table) {
            $table->dropColumn([
                'balance_type',
                'commission_amount',
                'returns_amount',
                'scheduled_processing_date',
                'processing_cycle',
                'balance_breakdown'
            ]);
        });
    }
};
