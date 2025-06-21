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
        $profile = $user->hasRole('siswa') ? $user->studentProfile : $user->profile;

        if (!$profile) {
            return null;
        }

        $filenameBase = $user->niss ?? \Str::slug($user->name);
        $filename = $filenameBase . '_' . time() . '.' . $file->getClientOriginalExtension();

        // Folder upload: public/photo_profiles
        $uploadFolder = public_path('photo_profiles');

        // ✅ Buat folder hanya jika belum ada
        if (!is_dir($uploadFolder)) {
            mkdir($uploadFolder, 0755, true);
        }

        // Hapus file lama jika ada
        if ($profile->photo_profile && file_exists(public_path($profile->photo_profile))) {
            unlink(public_path($profile->photo_profile));
        }

        // Pindahkan file ke folder tujuan
        $file->move($uploadFolder, $filename);

        // Simpan path relatif ke database
        $relativePath = 'photo_profiles/' . $filename;

        $profile->photo_profile = $relativePath;
        $profile->save();

        return $relativePath;
    }
}