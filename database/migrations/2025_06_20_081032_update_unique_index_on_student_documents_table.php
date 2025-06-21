<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUniqueIndexOnStudentDocumentsTable extends Migration
{
    public function up(): void
    {
        Schema::table('student_documents', function (Blueprint $table) {
            // Drop unique pada 'jenis_dokumen' jika sudah ada
            $table->dropUnique(['jenis_dokumen']); 

            // Tambahkan unique gabungan per siswa
            $table->unique(['student_profile_id', 'jenis_dokumen'], 'student_doc_unique_per_student');
        });
    }

    public function down(): void
    {
        Schema::table('student_documents', function (Blueprint $table) {
            $table->dropUnique('student_doc_unique_per_student');
        });
    }
}

