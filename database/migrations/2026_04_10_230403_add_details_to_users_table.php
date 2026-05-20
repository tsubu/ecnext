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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('postal_code')->nullable()->after('phone');
            $table->string('address1')->nullable()->after('postal_code');
            $table->string('address2')->nullable()->after('address1');
            $table->date('birth')->nullable()->after('address2');
            $table->string('gender')->nullable()->after('birth');
            $table->string('status')->default('active')->after('gender');
            $table->timestamp('last_login_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'postal_code', 'address1', 'address2',
                'birth', 'gender', 'status', 'last_login_at'
            ]);
        });
    }
};
