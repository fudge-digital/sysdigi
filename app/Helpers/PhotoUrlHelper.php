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

        $fullPath = public_path($path);

        return $path && file_exists($fullPath)
            ? asset($path)
            : asset('images/default-avatar.png');
    }
}