<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCoachCategory extends Model
{
    use HasFactory;

    protected $table = 'coach_student_category';

    protected $fillable = [
        'coach_id',
        'student_id',
        'kategori_umur',
        'jenis_kelamin',
    ];

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
