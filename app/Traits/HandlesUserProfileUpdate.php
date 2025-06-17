<?php
namespace App\Traits;

use App\Models\User;

trait HandlesUserProfileUpdate
{
    protected function updateUserProfile(User $user, array $data): void
    {
        if ($user->hasRole('siswa')) {
            $user->studentProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nama_panggilan' => $data['nama_panggilan'],
                    'email' => $data['email'],
                    'photo_profile' => $data['photo_profile'] ?? $user->getOriginalPhotoPath(),
                    'jenis_kelamin' => $data['jenis_kelamin'],
                    'alamat' => $data['alamat'],
                    'tempat_lahir' => $data['tempat_lahir'],
                    'tanggal_lahir' => $data['tanggal_lahir'],
                    'tinggi_badan' => $data['tinggi_badan'],
                    'berat_badan' => $data['berat_badan'],
                    'kategori_umur' => $data['kategori_umur'],
                    'asal_sekolah' => $data['asal_sekolah'],
                    'nomor_whatsapp' => $data['nomor_whatsapp'] ?? null,
                    'nomor_jersey' => $data['nomor_jersey'] ?? null,
                    'status_siswa' => $data['status_siswa'],
                ]
            );
        } else {
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'photo_profile' => $data['photo_profile'] ?? $user->getOriginalPhotoPath(),
                    'jabatan' => $data['jabatan'],
                    'lisensi' => $data['lisensi'],
                    'jenis_kelamin' => $data['jenis_kelamin'],
                    'status' => $data['status'],
                ]
            );
        }
    }
}