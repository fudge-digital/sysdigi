<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('documentUrl')) {
    /**
     * Mengembalikan URL dokumen siswa jika ada.
     *
     * @param \App\Models\User|null $user
     * @param string $jenis KK, AKTA, IJAZAH, NISN
     * @return string
     */
    function documentUrl($user, string $jenis): string
    {
        if (
            !$user ||
            !$user->hasRole('siswa') ||
            !$user->studentProfile ||
            !$user->studentProfile->documents
        ) {
            return '';
        }

        $dokumen = $user->studentProfile->documents
            ->firstWhere('jenis_dokumen', strtoupper($jenis));

        if (!$dokumen || !$dokumen->file_path) {
            return '';
        }

        $path = $dokumen->file_path;

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