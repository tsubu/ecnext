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
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->timestamp('scheduled_reward_at')->nullable()->after('is_approved');
            $table->timestamp('reward_issued_at')->nullable()->after('scheduled_reward_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropColumn(['scheduled_reward_at', 'reward_issued_at']);
        });
    }
};
