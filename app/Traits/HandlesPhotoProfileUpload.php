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

        // Jika belum ada profil, hentikan proses upload
        if (!$profile) {
            return null;
        }

        // Hapus foto lama jika ada
        $photoPath = public_path($profile->photo_profile);
        if ($profile->photo_profile && file_exists($photoPath)) {
            unlink($photoPath);
        }

        // Buat nama file unik
        $filenameBase = $user->niss ?? Str::slug($user->name);
        $filename = $filenameBase . '_' . time() . '.' . $file->getClientOriginalExtension();

        // Simpan file ke storage
        $destination = public_path('photo_profiles');
        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }

        $file->move($destination, $filename);

        // Simpan path relatif untuk penggunaan asset()
        $relativePath = 'photo_profiles/' . $filename;
        $profile->photo_profile = $relativePath;
        $profile->save();

        return $relativePath;
    }
}