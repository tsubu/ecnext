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
        Schema::table('shop_settings', function (Blueprint $table) {
            $table->string('timezone')->default('Asia/Tokyo')->after('address2');
            $table->boolean('points_enabled')->default(true)->after('tax_rate');
            $table->decimal('point_rate', 5, 2)->default(1.00)->comment('Percentage of purchase awarded as points')->after('points_enabled');
            $table->decimal('point_conversion_rate', 8, 2)->default(1.00)->comment('Value of 1 point in currency')->after('point_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_settings', function (Blueprint $table) {
            $table->dropColumn(['timezone', 'points_enabled', 'point_rate', 'point_conversion_rate']);
        });
    }
};
