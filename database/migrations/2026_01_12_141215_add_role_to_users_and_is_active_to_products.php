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
        // add role in users
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('email');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('stock');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
