<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('block_types', function (Blueprint $table) {
            $table->id();
            $table->string('type_key')->unique();
            $table->string('name');
            $table->json('schema')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('is_system')->default(false);
            $table->timestamps();
        });

        Schema::create('block_presets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('block_type_id')->constrained('block_types')->onDelete('cascade');
            $table->string('name');
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        Schema::create('block_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('block_type_id')->constrained('block_types')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('block_instances');
        Schema::dropIfExists('block_presets');
        Schema::dropIfExists('block_types');
    }
};
