<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->foreignId('admin_role_id')->constrained('admin_roles')->onDelete('cascade');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('admin_role_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_role_id')->constrained('admin_roles')->onDelete('cascade');
            $table->string('permission_key')->index();
            $table->timestamps();
            $table->unique(['admin_role_id', 'permission_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_role_permissions');
        Schema::dropIfExists('admins');
        Schema::dropIfExists('admin_roles');
    }
};
