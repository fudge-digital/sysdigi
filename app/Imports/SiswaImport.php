<?php

namespace App\Imports;

use App\Models\User;
use App\Models\StudentProfile;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SiswaImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Temukan user berdasarkan NISS, atau buat baru
        $user = User::updateOrCreate(
            ['niss' => $row['niss']],
            [
                'name' => $row['nama_lengkap'],
                'email' => $row['email'],
                'password' => Hash::make('bdg_2025##'),
            ]
        );

        // Tambahkan role siswa
        if (!$user->hasRole('siswa')) {
            $user->syncRoles(['siswa']);
        }

        // Pecah "Kategori Umur Jenis Kelamin"
        $tanggal_lahir = is_numeric($row['tanggal_lahir'])
    ? Date::excelToDateTimeObject($row['tanggal_lahir'])
    : Carbon::parse($row['tanggal_lahir']);

        // Update/Insert StudentProfile
        $user->studentProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'nama_panggilan' => $row['nama_panggilan'],
                'photo_profile' => $row['photo_profile'],
                'tempat_lahir' => $row['tempat_lahir'],
                'tanggal_lahir' => $tanggal_lahir,
                'alamat' => $row['alamat'],
                'tinggi_badan' => $row['tinggi_badan'],
                'berat_badan' => $row['berat_badan'],
                'asal_sekolah' => $row['asal_sekolah'],
                'jenis_kelamin' => $row['jenis_kelamin'],
                'nomor_whatsapp' => $row['nomor_whatsapp'],
                'nomor_jersey' => $row['nomor_jersey'],
                'status_siswa' => $row['status_siswa'],
            ]
        );

        return $user;
    }

    public function rules(): array
    {
        return [
            'niss' => ['required', 'string'],
            'email' => ['required', 'email'],
            'nama_lengkap' => ['required', 'string'],
            'nama_panggilan' => ['nullable', 'string'],
            'photo_profile' => ['nullable', 'string'],
            'tempat_lahir' => ['nullable', 'string'],
            'tanggal_lahir' => ['nullable'], 
            'alamat' => ['nullable', 'string'],
            'tinggi_badan' => ['nullable', 'numeric'],
            'berat_badan' => ['nullable', 'numeric'],
            'asal_sekolah' => ['nullable', 'string'],
            'jenis_kelamin' => ['required'],
            'nomor_whatsapp' => ['nullable', 'string'],
            'nomor_jersey' => ['nullable'],
            'status_siswa' => ['nullable', 'string'],
        ];
    }
}

