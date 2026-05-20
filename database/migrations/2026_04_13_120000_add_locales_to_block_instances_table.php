<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('block_instances', function (Blueprint $table) {
            $table->json('name_locales')->nullable()->after('name');
            $table->json('settings_locales')->nullable()->after('settings');
        });
    }

    public function down(): void
    {
        Schema::table('block_instances', function (Blueprint $table) {
            $table->dropColumn(['name_locales', 'settings_locales']);
        });
    }
};
