<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('photoProfileUrl')) {
    function photoProfileUrl($user)
    {
        if (!$user || !$user->profile && !$user->studentProfile) {
            return asset('images/default-profile.png'); // fallback
        }
        
        $path = $user->hasRole('siswa')
            ? $user->studentProfile?->photo_profile
            : $user->profile?->photo_profile;

        return $path && Storage::disk('public')->exists($path)
            ? asset($path)
            : asset('images/default-avatar.png');
    }
}