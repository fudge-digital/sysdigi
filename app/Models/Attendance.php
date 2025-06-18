<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'coach_id',
        'jenis_absen',
        'tanggal',
        'jam',
        'tempat_latihan',
        'tempat_latihan_lainnya',
        'foto_latihan',
        'keterangan',
        'jenis_pertandingan',
        'nama_turnamen',
        'klub_tanding',
        'tempat_pertandingan',
        'hasil_skor',
        'foto_pertandingan',
    ];

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'attendance_student', 'attendance_id', 'student_id');
    }
}
