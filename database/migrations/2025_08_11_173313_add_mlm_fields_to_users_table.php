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
            $table->string('referral_code')->unique()->nullable();
            $table->unsignedBigInteger('sponsor_id')->nullable();
            $table->decimal('wallet_balance', 15, 2)->default(0);
            $table->decimal('commission_balance', 15, 2)->default(0);
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');

            $table->foreign('sponsor_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['sponsor_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['sponsor_id']);
            $table->dropColumn([
                'referral_code',
                'sponsor_id',
                'wallet_balance',
                'commission_balance',
                'phone',
                'address',
                'status'
            ]);
        });
    }
};
