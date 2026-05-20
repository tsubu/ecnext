<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. block_instances updates
        Schema::table('block_instances', function (Blueprint $table) {
            $table->boolean('is_shared')->default(true)->after('is_active');
            $table->unsignedBigInteger('owner_id')->nullable()->after('is_shared');
            $table->string('owner_type')->nullable()->after('owner_id');
            $table->index(['owner_id', 'owner_type']);
        });

        // 2. page_layouts -> morphable layouts
        Schema::table('page_layouts', function (Blueprint $table) {
            $table->unsignedBigInteger('layoutable_id')->nullable()->after('page_id');
            $table->string('layoutable_type')->nullable()->after('layoutable_id');
            $table->json('settings_override')->nullable()->after('block_instance_id');
            $table->index(['layoutable_id', 'layoutable_type']);
        });

        // Data Migration: Copy page_id to layoutable columns
        DB::table('page_layouts')->update([
            'layoutable_id' => DB::raw('page_id'),
            'layoutable_type' => 'App\\Models\\Page'
        ]);

        // Note: We keep page_id for now to avoid breaking existing queries until code is updated.
        // We will make it nullable.
        Schema::table('page_layouts', function (Blueprint $table) {
            $table->unsignedBigInteger('page_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('block_instances', function (Blueprint $table) {
            $table->dropColumn(['is_shared', 'owner_id', 'owner_type']);
        });

        Schema::table('page_layouts', function (Blueprint $table) {
            $table->dropIndex(['layoutable_id', 'layoutable_type']);
            $table->dropColumn(['layoutable_id', 'layoutable_type', 'settings_override']);
        });
    }
};
