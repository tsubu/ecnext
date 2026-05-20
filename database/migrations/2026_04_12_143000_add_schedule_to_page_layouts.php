<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_layouts', function (Blueprint $table) {
            $table->timestamp('starts_at')->nullable()->after('sort_order');
            $table->timestamp('expires_at')->nullable()->after('starts_at');
        });
    }

    public function down(): void
    {
        Schema::table('page_layouts', function (Blueprint $table) {
            $table->dropColumn(['starts_at', 'expires_at']);
        });
    }
};
