<?php

namespace App\Traits;

use App\Models\User;

trait HandlesProtectedFields
{
    protected function protectStudentFields(array $data, User $user): array
    {
        if (!$user->hasRole('siswa')) {
            return $data;
        }

        $profile = $user->studentProfile;

        $data['jenis_kelamin']   = $profile->jenis_kelamin;
        $data['status_siswa']    = $profile->status_siswa;
        $data['nomor_jersey']    = $profile->nomor_jersey;
        $data['kategori_umur']   = $profile->kategori_umur;
        $data['tanggal_lahir']   = $profile->tanggal_lahir;

        return $data;
    }
}
