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
        Schema::table('pages', function (Blueprint $table) {
            $table->foreignId('page_category_id')->nullable()->after('id')->constrained('page_categories')->onDelete('set null');
            $table->string('type')->default('default')->after('title'); // 'default', 'legal'
            $table->longText('content')->nullable()->after('type');
            $table->json('legal_data')->nullable()->after('content');
            $table->timestamp('published_at')->nullable()->after('is_published');
            $table->timestamp('expired_at')->nullable()->after('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropForeign(['page_category_id']);
            $table->dropColumn([
                'page_category_id',
                'type',
                'content',
                'legal_data',
                'published_at',
                'expired_at'
            ]);
        });
    }
};
