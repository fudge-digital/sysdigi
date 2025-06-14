<?php

namespace App\Helpers;

class UmurHelper
{
    public static function hitungKategoriUmur(string $tanggalLahir): string
    {
        $tahun = \Carbon\Carbon::parse($tanggalLahir)->year;
        $tahunSekarang = now()->year;
        $usia = $tahunSekarang - $tahun;

        return match (true) {
            $usia < 8 => 'Dibawah U8',
            $usia >= 19 && $usia <= 24 => 'Pra Divisi',
            $usia >= 25 && $usia <= 35 => 'Divisi',
            $usia > 35 => 'Veteran',
            default => 'U' . $usia,
        };
    }
}
