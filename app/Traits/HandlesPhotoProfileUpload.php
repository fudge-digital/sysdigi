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
        $photoPath = $profile->photo_profile;
        if ($photoPath && Storage::disk('public')->exists($photoPath)) {
            Storage::disk('public')->delete($photoPath);
        }

        // Buat nama file unik
        $filenameBase = $user->niss ?? Str::slug($user->name);
        $filename = $filenameBase . '_' . time() . '.' . $file->getClientOriginalExtension();

        // Simpan file ke storage
        $path = $file->storeAs('photo_profiles', $filename, 'public');

        // Simpan path ke dalam model profil
        $profile->photo_profile = $path;
        $profile->save();

        return $path;
    }
}