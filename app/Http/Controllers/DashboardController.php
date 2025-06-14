<?php

// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\UserProfile;
use App\Exports\SiswaExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole(['admin', 'manajemen'])) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('coach')) {
            return redirect()->route('coach.dashboard');
        } elseif ($user->hasRole('siswa')) {
            return view('dashboards.siswa');
        }

        abort(403);
    }

    public function coachDashboard(Request $request)
    {
        $coach = auth()->user();

        // Dapatkan kategori yang dihandle oleh coach
        $handledCategories = DB::table('coach_student_category')
            ->where('coach_id', $coach->id)
            ->get(['kategori_umur', 'jenis_kelamin']);

        if ($handledCategories->isEmpty()) {
            return view('dashboards.coach', [
                'students' => collect(),
                'kategoriUmurOptions' => [],
                'tahunOptions' => [],
                'totalAktif' => 0,
                'totalNonAktif' => 0,
            ]);
        }

        // Buat array untuk whereIn
        $handledPairs = $handledCategories->map(fn ($c) => $c->kategori_umur . '|' . $c->jenis_kelamin)->toArray();

        $status = $request->get('status', 'aktif'); // default 'aktif'

        $mappedStatus = $status === 'nonaktif' ? 'tidak aktif' : 'aktif';

        // Query utama untuk siswa aktif
        $students = User::role('siswa')
            ->join('student_profiles', 'users.id', '=', 'student_profiles.user_id')
            ->with('studentProfile')
            ->where('student_profiles.status_siswa', $mappedStatus)
            ->whereIn(
                DB::raw("CONCAT(kategori_umur, '|', jenis_kelamin)"),
                $handledPairs
            )
            ->when($request->filled('kategori_umur'), fn ($q) => $q->where('student_profiles.kategori_umur', $request->kategori_umur))
            ->when($request->filled('tahun_lahir'), fn ($q) => $q->whereYear('student_profiles.tanggal_lahir', $request->tahun_lahir))
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($q2) use ($request) {
                    $search = $request->search;
                    $q2->where('users.name', 'like', "%$search%")
                        ->orWhere('student_profiles.nama_panggilan', 'like', "%$search%")
                        ->orWhere('student_profiles.nomor_jersey', 'like', "%$search%");
                });
            })
            ->orderBy('users.niss')
            ->select('users.*', 'student_profiles.*')
            ->paginate(10)
            ->appends($request->query());

        // Ambil kategori_umur unik untuk dropdown
        $kategoriUmurOptions = $handledCategories->pluck('kategori_umur')->unique();

        // Ambil tahun lahir unik untuk dropdown
        $tahunOptions = DB::table('student_profiles')
            ->whereIn(
                DB::raw("CONCAT(kategori_umur, '|', jenis_kelamin)"),
                $handledPairs
            )
            ->selectRaw('YEAR(tanggal_lahir) as tahun')
            ->groupBy('tahun')
            ->orderByDesc('tahun')
            ->pluck('tahun');

        // === Total Aktif dan Tidak Aktif berdasarkan kategori yang dihandle ===
        $totalAktif = DB::table('student_profiles')
            ->where('status_siswa', 'aktif')
            ->whereIn(DB::raw("CONCAT(kategori_umur, '|', jenis_kelamin)"), $handledPairs)
            ->count();

        $totalNonAktif = DB::table('student_profiles')
            ->where('status_siswa', '!=', 'aktif')
            ->whereIn(DB::raw("CONCAT(kategori_umur, '|', jenis_kelamin)"), $handledPairs)
            ->count();

        return view('dashboards.coach', [
            'students' => $students,
            'kategoriUmurOptions' => $kategoriUmurOptions,
            'tahunOptions' => $tahunOptions,
            'totalAktif' => $totalAktif,
            'totalNonAktif' => $totalNonAktif,
            'status' => $status, // kirim untuk blade
        ]);
    }


    public function adminDashboard()
    {
        // Total seluruh siswa
        $totalSiswa = User::role('siswa')->count();

        // Total siswa aktif dan tidak aktif
        $totalAktif = StudentProfile::where('status_siswa', 'aktif')->count();
        $totalNonAktif = StudentProfile::where('status_siswa', 'tidak aktif')->count();

        // Total pelatih berdasarkan jabatan (dari profiles table)
        $totalPelatihPerJabatan = UserProfile::whereHas('user', fn ($q) => $q->role('coach'))
            ->select('jabatan', DB::raw('count(*) as total'))
            ->groupBy('jabatan')
            ->get();

        return view('dashboards.admin', compact(
            'totalSiswa',
            'totalAktif',
            'totalNonAktif',
            'totalPelatihPerJabatan'
        ));
    }


    public function exportFilteredStudents(Request $request)
    {
        $coach = auth()->user();

        $handledCategories = DB::table('coach_student_category')
            ->where('coach_id', $coach->id)
            ->get(['kategori_umur', 'jenis_kelamin']);

        $handledPairs = $handledCategories->map(fn ($c) => $c->kategori_umur . '|' . $c->jenis_kelamin)->toArray();

        $students = User::role('siswa')
            ->join('student_profiles', 'users.id', '=', 'student_profiles.user_id')
            ->with('studentProfile')
            ->where('student_profiles.status_siswa', 'aktif')
            ->whereIn(DB::raw("CONCAT(kategori_umur, '|', jenis_kelamin)"), $handledPairs)
            ->when($request->filled('kategori_umur'), fn ($q) => $q->where('student_profiles.kategori_umur', $request->kategori_umur))
            ->when($request->filled('tahun_lahir'), fn ($q) => $q->whereYear('student_profiles.tanggal_lahir', $request->tahun_lahir))
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($q2) use ($search) {
                    $q2->where('users.name', 'like', "%$search%")
                        ->orWhere('student_profiles.nama_panggilan', 'like', "%$search%")
                        ->orWhere('student_profiles.nomor_jersey', 'like', "%$search%")
                        ->orWhere('users.niss', 'like', "%$search%");
                });
            })
            ->select('users.*', 'student_profiles.kategori_umur', 'student_profiles.jenis_kelamin') // penting agar tidak ambigu
            ->get();

        return Excel::download(new SiswaExport($students), 'siswa-filtered.xlsx');
    }


    public function logout(Request $request)
    {

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');

    }
}
