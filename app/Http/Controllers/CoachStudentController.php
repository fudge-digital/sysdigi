<?php 

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CoachStudentController extends Controller
{
    public function index()
    {
        $coach = Auth::user();

        // Hanya role coach yang boleh mengakses
        abort_unless($coach->hasRole('coach'), 403);

        // Ambil kategori_umur & jenis_kelamin yang ditangani coach
        $handledCategories = DB::table('coach_student_category')
            ->where('coach_id', $coach->id)
            ->select('kategori_umur', 'jenis_kelamin')
            ->get();

        if ($handledCategories->isEmpty()) {
            $students = collect(); // Kosong jika tidak ada kategori
        } else {
            $students = DB::table('users')
                ->join('student_profiles', 'users.id', '=', 'student_profiles.user_id')
                ->where('student_profiles.status_siswa', 'aktif')
                ->whereIn(
                    DB::raw("CONCAT(kategori_umur, '|', jenis_kelamin)"),
                    $handledCategories->map(fn($cat) => $cat->kategori_umur . '|' . $cat->jenis_kelamin)->toArray()
                )
                ->select('users.id', 'users.name', 'student_profiles.kategori_umur', 'student_profiles.jenis_kelamin')
                ->orderBy('student_profiles.kategori_umur')
                ->orderBy('student_profiles.jenis_kelamin')
                ->get();
        }

        return view('coach.students', compact('students'));
    }
}
