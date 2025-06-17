<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('photoProfileUrl')) {
    function photoProfileUrl($user)
    {
        if (!$user || (!$user->profile && !$user->studentProfile)) {
            return asset('photo_profiles/default-profile.png');
        }

        $path = $user->hasRole('siswa')
            ? $user->studentProfile?->photo_profile
            : $user->profile?->photo_profile;

        if (!$path) {
            return asset('photo_profiles/default-avatar.png');
        }

        // Check apakah file disimpan di local (storage/public) atau production (public/)
        if (app()->environment('local')) {
            // Path untuk local (via storage/public)
            return Storage::disk('public')->exists($path)
                ? asset('storage/' . $path)
                : asset('photo_profiles/default-avatar.png');
        }

        // Path untuk hosting (langsung di public/photo_profiles)
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($path, '/');

        return file_exists($fullPath)
            ? asset($path)
            : asset('photo_profiles/default-avatar.png');
    }
}