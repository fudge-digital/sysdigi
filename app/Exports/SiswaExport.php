<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaExport implements FromCollection, WithHeadings
{
    protected Collection $students;

    public function __construct(Collection $students)
    {
        $this->students = $students;
    }

    public function collection()
    {
        return $this->students->map(function ($user) {
                return [
                    'NISS' => $user->niss,
                    'Email' => $user->email,
                    'Nama Lengkap' => $user->name,
                    'Nama Panggilan' => $user->studentProfile->nama_panggilan ?? '-',
                    'Photo Profile' => $user->studentProfile->photo_profile ?? '-',
                    'Tempat Lahir' => $user->studentProfile->tempat_lahir ?? '-',
                    'Tanggal Lahir' => $user->studentProfile->tanggal_lahir ?? '-',
                    'Alamat' => $user->studentProfile->alamat ?? '-',
                    'Tinggi Badan' => $user->studentProfile->tinggi_badan ?? '-',
                    'Berat Badan' => $user->studentProfile->berat_badan ?? '-',
                    'Asal Sekolah' => $user->studentProfile->asal_sekolah ?? '-',
                    'Kategori Umur' => 
                        ($user->studentProfile->kategori_umur ?? '-') . ' ' . 
                        ($user->studentProfile->jenis_kelamin ?? '-'),
                    'Nomor WhatsApp' => $user->studentProfile->nomor_whatsapp ?? '-',
                    'Nomor Jersey' => $user->studentProfile->nomor_jersey ?? '-',
                    'Status Siswa' => $user->studentProfile->status_siswa ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'NISS',
            'Email',
            'Nama Lengkap',
            'Nama Panggilan',
            'Photo',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Alamat',
            'Tinggi Badan',
            'Berat Badan',
            'Asal Sekolah',
            'Kategori Umur',
            'Nomor WhatsApp',
            'Nomor Jersey',
            'Status Siswa',
        ];
    }
}
