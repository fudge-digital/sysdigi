<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentDocument extends Model
{
    protected $fillable = ['student_profile_id', 'jenis_dokumen', 'file_path'];

    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class);
    }
    
}