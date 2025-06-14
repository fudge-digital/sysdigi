<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\UserProfile;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'niss',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = ['photo_profile_url'];

    public function username()
    {
        return 'niss';
    }

    // Untuk Coach → semua siswa yang dilatih berdasarkan pivot coach_student_category
    public function assignedStudents(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'coach_student_category', 'coach_id', 'student_id')
            ->withPivot(['kategori_umur', 'jenis_kelamin'])
            ->withTimestamps();
    }

    // Untuk Siswa → coach yang menangani dia
    public function assignedCoach(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'coach_student_category', 'student_id', 'coach_id')
            ->withPivot(['kategori_umur', 'jenis_kelamin'])
            ->withTimestamps();
    }

    // Coach → kategori yang ditangani, dari tabel coach_student_category (bisa satu coach menangani banyak kategori)
    public function coachStudentCategories(): HasMany
    {
        return $this->hasMany(CoachStudentCategory::class, 'coach_id');
    }

    // Siswa → entri coach_student_category yang mengarah padanya
    public function studentCoachCategory(): HasMany
    {
        return $this->hasMany(CoachStudentCategory::class, 'student_id');
    }

    // Jika kamu memiliki tabel terpisah untuk kategori yang bisa ditangani oleh coach (bukan yang sudah diset siswa)
    // Misalnya: tabel `coach_categories` berisi data: coach_id, kategori_umur, jenis_kelamin
    public function handledCategories(): HasMany
    {
        return $this->hasMany(CoachCategory::class);
    }

    public function getStatusAttribute()
    {
        if ($this->hasRole('siswa')) {
            return $this->studentProfile->status_siswa ?? null;
        }

        return $this->profile->status ?? null;
    }

    public function getProfileUnifiedAttribute()
    {
        return $this->hasRole('siswa') ? $this->studentProfile : $this->profile;
    }

    public function getOriginalPhotoPath(): ?string
    {
        return $this->hasRole('siswa')
            ? $this->studentProfile?->getRawOriginal('photo_profile')
            : $this->profile?->getRawOriginal('photo_profile');
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class);
    }
}
