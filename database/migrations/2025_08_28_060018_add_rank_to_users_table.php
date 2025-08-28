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
            $table->enum('rank', ['Guest', 'Member', 'Counsellor', 'Leader', 'Trainer', 'Senior Trainer'])
                  ->default('Guest')
                  ->after('commission_balance');
            $table->timestamp('rank_achieved_at')->nullable()->after('rank');
            $table->decimal('total_investment', 15, 2)->default(0)->after('rank_achieved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['rank', 'rank_achieved_at', 'total_investment']);
        });
    }
};
