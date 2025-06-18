<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            // Relasi ke coach
            $table->foreignId('coach_id')->constrained('users')->onDelete('cascade');

            // Jenis absen: 'latihan' atau 'pertandingan'
            $table->enum('jenis_absen', ['latihan', 'pertandingan']);

            // Field umum
            $table->date('tanggal');
            $table->time('jam');

            // ========== Field khusus latihan ==========
            $table->string('tempat_latihan')->nullable();
            $table->string('tempat_latihan_lainnya')->nullable();
            $table->string('foto_latihan')->nullable();
            $table->text('keterangan')->nullable();

            // ========== Field khusus pertandingan ==========
            $table->enum('jenis_pertandingan', ['scrimmage', 'turnamen'])->nullable();
            $table->string('nama_turnamen')->nullable();
            $table->string('klub_tanding')->nullable();
            $table->string('tempat_pertandingan')->nullable();
            $table->string('hasil_skor')->nullable();
            $table->string('foto_pertandingan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
}
