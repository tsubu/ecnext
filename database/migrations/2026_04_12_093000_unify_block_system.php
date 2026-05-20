<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add slug to block_instances
        Schema::table('block_instances', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
        });

        // 2. Register system block type for Static CMS
        $typeId = DB::table('block_types')->insertGetId([
            'type_key' => 'static_cms',
            'name' => 'CMS コンテンツ',
            'schema' => json_encode(['body' => 'textarea', 'raw_html' => 'boolean']),
            'icon' => 'document-text',
            'is_system' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Migrate data from cms_blocks to block_instances
        $cmsBlocks = DB::table('cms_blocks')->get();
        foreach ($cmsBlocks as $block) {
            DB::table('block_instances')->insert([
                'block_type_id' => $typeId,
                'name' => $block->title,
                'slug' => $block->slug,
                'settings' => $block->content_json,
                'is_active' => $block->is_active,
                'created_at' => $block->created_at,
                'updated_at' => $block->updated_at,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('block_instances', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        DB::table('block_types')->where('type_key', 'static_cms')->delete();
    }
};
