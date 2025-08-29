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
        Schema::table('users', function (Blueprint $table) {
            // Add new isolated wallet fields
            $table->decimal('investment_balance', 15, 2)->default(0)->after('commission_balance');
            $table->decimal('return_balance', 15, 2)->default(0)->after('investment_balance');
            $table->decimal('withdrawable_balance', 15, 2)->default(0)->after('return_balance');

            // Add withdrawal processing fields
            $table->timestamp('last_withdrawal_processed_at')->nullable()->after('withdrawable_balance');
            $table->decimal('pending_withdrawal_amount', 15, 2)->default(0)->after('last_withdrawal_processed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'investment_balance',
                'return_balance',
                'withdrawable_balance',
                'last_withdrawal_processed_at',
                'pending_withdrawal_amount'
            ]);
        });
    }
};
