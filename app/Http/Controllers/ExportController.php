<?php

namespace App\Http\Controllers;

use App\Exports\SiswaExport;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportSiswa(Request $request)
    {
        $format = $request->query('format', 'excel');
        $timestamp = now()->format('Ymd_His');

        // Filter data siswa (bisa disesuaikan untuk role coach atau admin)
        $query = User::role('siswa')
            ->with('studentProfile')
            ->join('student_profiles', 'users.id', '=', 'student_profiles.user_id')
            ->select('users.*'); // penting agar tidak ambigu

        if ($request->filled('kategori_umur')) {
            $query->where('student_profiles.kategori_umur', $request->kategori_umur);
        }

        if ($request->filled('tahun_lahir')) {
            $query->whereYear('student_profiles.tanggal_lahir', $request->tahun_lahir);
        }

        if ($request->filled('status_siswa')) {
            $status = $request->status_siswa;

            // Mapping 'nonaktif' kembali menjadi 'tidak aktif'
            if ($status === 'nonaktif') {
                $status = 'tidak aktif';
            }

            if (in_array($status, ['aktif', 'tidak aktif'])) {
                $query->where('student_profiles.status_siswa', $status);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%$search%")
                    ->orWhere('student_profiles.nama_panggilan', 'like', "%$search%")
                    ->orWhere('users.niss', 'like', "%$search%")
                    ->orWhere('student_profiles.nomor_jersey', 'like', "%$search%");
            });
        }

        $students = $query->get();

        // Export ke PDF
        if ($format === 'pdf') {
            $pdf = Pdf::loadView('exports.siswa-pdf', ['siswa' => $students]);
            return $pdf->download("data_siswa_{$timestamp}.pdf");
        }

        // Export ke Excel
        return Excel::download(new SiswaExport($students), "data_siswa_{$timestamp}.xlsx");
    }
}
