<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('photoProfileUrl')) {
    function photoProfileUrl($user)
    {
        if (!$user || (!$user->profile && !$user->studentProfile)) {
            return asset('images/default-profile.png'); // fallback umum
        }

        $path = $user->hasRole('siswa')
            ? $user->studentProfile?->photo_profile
            : $user->profile?->photo_profile;

        // Cek apakah file benar-benar ada di folder public
        return $path && file_exists(public_path($path))
            ? asset($path)
            : asset('images/default-avatar.png');
    }
}