<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Move existing wallet_balance to investment_balance
        DB::statement('UPDATE users SET investment_balance = wallet_balance WHERE wallet_balance > 0');

        // Move existing commission_balance to withdrawable_balance
        DB::statement('UPDATE users SET withdrawable_balance = commission_balance WHERE commission_balance > 0');

        // Reset wallet_balance to 0 (it will be deprecated)
        DB::statement('UPDATE users SET wallet_balance = 0');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore wallet_balance from investment_balance
        DB::statement('UPDATE users SET wallet_balance = investment_balance WHERE investment_balance > 0');

        // Reset the new balance fields
        DB::statement('UPDATE users SET investment_balance = 0, return_balance = 0, withdrawable_balance = 0, pending_withdrawal_amount = 0');
    }
};
