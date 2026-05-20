<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('coupon_id')->nullable()->constrained()->onDelete('set null')->after('shipping_method_id');
            $table->decimal('coupon_discount', 12, 2)->default(0)->after('coupon_id');
            $table->decimal('points_used', 12, 2)->default(0)->after('coupon_discount');
            $table->decimal('points_discount', 12, 2)->default(0)->after('points_used');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('coupon_id');
            $table->dropColumn(['coupon_discount', 'points_used', 'points_discount']);
        });
    }
};
