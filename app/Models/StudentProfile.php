<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_panggilan',
        'email',
        'photo_profile',
        'jenis_kelamin',
        'alamat',
        'tempat_lahir',
        'tanggal_lahir',
        'tinggi_badan',
        'berat_badan',
        'kategori_umur',
        'asal_sekolah',
        'nomor_whatsapp',
        'nomor_jersey',
        'status_siswa',
    ];

    protected static function booted()
    {
        static::saving(function ($profile) {
            if ($profile->tanggal_lahir) {
                $year = now()->year - \Carbon\Carbon::parse($profile->tanggal_lahir)->year;

                $profile->kategori_umur = match (true) {
                    $year < 8 => 'Dibawah U8',
                    $year >= 8 && $year <= 18 => 'U' . $year,
                    $year >= 19 && $year <= 24 => 'Pra Divisi',
                    $year >= 25 && $year <= 35 => 'Divisi',
                    default => 'Veteran',
                };
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->hasMany(StudentDocument::class);
    }
}
