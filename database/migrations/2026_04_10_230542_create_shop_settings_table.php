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
        Schema::create('shop_settings', function (Blueprint $table) {
            $table->id();
            
            // Basic Info
            $table->string('shop_name');
            $table->string('company_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('logo_path')->nullable();

            // Trade Law (特定商取引法)
            $table->string('trade_law_manager')->nullable();
            $table->string('trade_law_address')->nullable();
            $table->string('trade_law_tel')->nullable();
            $table->string('trade_law_email')->nullable();
            $table->text('trade_law_price_info')->nullable();
            $table->text('trade_law_payment_methods')->nullable();
            $table->text('trade_law_delivery_info')->nullable();
            $table->text('trade_law_return_policy')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_settings');
    }
};
