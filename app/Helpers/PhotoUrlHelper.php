<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('photoProfileUrl')) {
    function photoProfileUrl($user)
    {
        $default = 'photo_profiles/default-avatar.png';

        if (!$user) {
            return asset($default);
        }

        // Ambil path dari studentProfile atau profile
        $path = null;
        if ($user->hasRole('siswa')) {
            $path = optional($user->studentProfile)->photo_profile;
        } else {
            $path = optional($user->profile)->photo_profile;
        }

        if (!$path) {
            return asset($default);
        }

        // Jika di local: file di simpan via Storage
        if (app()->environment('local')) {
            $fullPath = public_path($path);
            return file_exists($fullPath)
                ? asset($path)
                : asset($default);
        }

        // Jika di production: file langsung ada di public/
        return file_exists(public_path($path))
            ? asset($path)
            : asset($default);
    }
}