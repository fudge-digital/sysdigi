<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\User;

trait HandlesPhotoProfileUpload
{
    protected function handlePhotoProfileUpload(Request $request, User $user): ?string
    {
        if (!$request->hasFile('photo_profile')) {
            return null;
        }

        $file = $request->file('photo_profile');

        // Ambil profil sesuai role
        $profile = $user->hasRole('siswa') ? $user->studentProfile : $user->profile;

        if (!$profile) {
            return null;
        }

        // Buat nama file unik
        $filenameBase = $user->niss ?? \Str::slug($user->name);
        $filename = $filenameBase . '_' . time() . '.' . $file->getClientOriginalExtension();

        // Path tujuan upload
        $uploadFolder = '/photo_profiles';
        $destinationPath = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . $uploadFolder;

        // Buat folder jika belum ada
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Hapus file lama jika ada
        if ($profile->photo_profile) {
            $oldPath = $destinationPath . '/' . basename($profile->photo_profile);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        // Simpan file ke folder tujuan
        $file->move($destinationPath, $filename);

        // Simpan path relatif ke database
        $relativePath = ltrim($uploadFolder . '/' . $filename, '/');
        $profile->photo_profile = $relativePath;
        $profile->save();

        return $relativePath;
    }
}