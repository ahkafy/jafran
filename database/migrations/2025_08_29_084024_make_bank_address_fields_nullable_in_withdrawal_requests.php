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
            $table->string('bank_address')->nullable()->change();
            $table->string('bank_city')->nullable()->change();
            $table->string('bank_state')->nullable()->change();
            $table->string('bank_zip_code')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('withdrawal_requests', function (Blueprint $table) {
            $table->string('bank_address')->nullable(false)->change();
            $table->string('bank_city')->nullable(false)->change();
            $table->string('bank_state')->nullable(false)->change();
            $table->string('bank_zip_code')->nullable(false)->change();
        });
    }
};
