<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review_reward_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('min_rating')->default(3);
            $table->string('reward_type'); // 'coupon' or 'point'
            $table->decimal('reward_value', 12, 2); // Point amount or Coupon ID/Discount
            $table->integer('coupon_expiry_days')->nullable(); // For auto-generated unique coupons
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_reward_settings');
    }
};
