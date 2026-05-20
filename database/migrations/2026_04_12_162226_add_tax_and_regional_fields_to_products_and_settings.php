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
            $table->string('country_code')->default('JP')->after('timezone');
            $table->string('currency_code')->default('JPY')->after('country_code');
            $table->enum('global_tax_type', ['inclusive', 'exclusive'])->default('exclusive')->after('tax_rate');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('tax_rule_id')->nullable()->constrained('tax_rules')->nullOnDelete()->after('meta_description');
            $table->decimal('individual_tax_rate', 5, 2)->nullable()->after('tax_rule_id');
            $table->enum('tax_type', ['inherit', 'inclusive', 'exclusive'])->default('inherit')->after('individual_tax_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['tax_rule_id']);
            $table->dropColumn(['tax_rule_id', 'individual_tax_rate', 'tax_type']);
        });

        Schema::table('shop_settings', function (Blueprint $table) {
            $table->dropColumn(['country_code', 'currency_code', 'global_tax_type']);
        });
    }
};
