<?php

namespace App\Http\Controllers;

use App\Exports\SiswaExport;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    public function exportSiswa(Request $request)
    {
        $coach = Auth::user();

        $format = $request->query('format', 'excel');
        $timestamp = now()->format('Ymd_His');

        // Ambil semua siswa yang ditangani coach (relasi students)
        $query = $coach->assignedStudents()->with('studentProfile');

        // Filter berdasarkan student_profile
        $query->whereHas('studentProfile', function ($q) use ($request) {
            if ($request->filled('kategori_umur')) {
                $q->where('kategori_umur', $request->kategori_umur);
            }

            if ($request->filled('tahun_lahir')) {
                $q->whereYear('tanggal_lahir', $request->tahun_lahir);
            }

            if ($request->filled('status_siswa')) {
                $status = $request->status_siswa === 'nonaktif' ? 'tidak aktif' : $request->status_siswa;
                if (in_array($status, ['aktif', 'tidak aktif'])) {
                    $q->where('status_siswa', $status);
                }
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $q->where(function ($q2) use ($search) {
                    $q2->where('nama_panggilan', 'like', "%$search%")
                        ->orWhere('nomor_jersey', 'like', "%$search%");
                });
            }
        });

        // Pencarian dari kolom utama (name/niss)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('niss', 'like', "%$search%");
            });
        }

        $students = $query->get();

        // Export PDF
        if ($format === 'pdf') {
            $pdf = Pdf::loadView('exports.siswa-pdf', ['siswa' => $students]);
            return $pdf->download("data_siswa_{$timestamp}.pdf");
        }

        // Export Excel
        return Excel::download(new SiswaExport($students), "data_siswa_{$timestamp}.xlsx");
    }
}
