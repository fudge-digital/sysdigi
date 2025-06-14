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
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn('nama');
        });

        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn('nama_lengkap');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->string('nama')->nullable();
        });

        Schema::table('student_profiles', function (Blueprint $table) {
            $table->string('nama_lengkap')->nullable();
        });
    }
};
