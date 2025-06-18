<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Http\Requests\StoreAttendanceRequest;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = auth()->user()
            ->attendances()
            ->with('students')
            ->latest()
            ->paginate(10);

        return view('coach.absensi.index', compact('attendances'));
    }

    public function create(Request $request)
    {
        $query = auth()->user()->handledStudents();

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhereHas('studentProfile', function ($q2) use ($search) {
                    $q2->where('nama_panggilan', 'like', "%{$search}%");
                });
            });
        }

        $students = $query
        ->join('student_profiles', 'users.id', '=', 'student_profiles.user_id')
        ->with('studentProfile')
        ->orderBy('student_profiles.kategori_umur')
        ->orderBy('users.name')
        ->select('users.*') // pastikan hanya ambil kolom dari tabel `users`
        ->get();

        return view('coach.absensi.create', compact('students'));
    }

    public function store(StoreAttendanceRequest $request)
    {
        try {
            $data = $request->validated();

            $attendance = new Attendance();
            $attendance->coach_id = auth()->id();
            $attendance->jenis_absen = $data['jenis_absen'];
            $attendance->tanggal = $data['tanggal'];
            $attendance->jam = $data['jam'];

            if ($data['jenis_absen'] === 'latihan') {
                $attendance->tempat_latihan = $data['tempat_latihan'];
                $attendance->tempat_latihan_lainnya = $data['tempat_latihan_lainnya'] ?? null;
                $attendance->keterangan = $data['keterangan'] ?? null;

                if ($request->hasFile('foto_latihan')) {
                    $attendance->foto_latihan = $request->file('foto_latihan')->store('absensi/latihan', 'public');
                }
            }

            if ($data['jenis_absen'] === 'pertandingan') {
                $attendance->jenis_pertandingan = $data['jenis_pertandingan'];
                $attendance->nama_turnamen = $data['jenis_pertandingan'] === 'turnamen' ? $data['nama_turnamen'] : null;
                $attendance->klub_tanding = $data['klub_tanding'];
                $attendance->tempat_pertandingan = $data['tempat_pertandingan'];
                $attendance->hasil_skor = $data['hasil_skor'];

                if ($request->hasFile('foto_pertandingan')) {
                    $attendance->foto_pertandingan = $request->file('foto_pertandingan')->store('absensi/pertandingan', 'public');
                }
            }

            $attendance->save();

            $selected = $request->input('selected_siswa_hadir'); // string "1,2,3"
            $studentIds = $selected ? explode(',', $selected) : [];
            $attendance->students()->sync($studentIds);

            return redirect()->route('coach.absensi.index')->with('success', 'Absensi berhasil disimpan.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan absensi: ' . $e->getMessage());
        }
    }

    public function show(Attendance $attendance)
    {
        $attendance->load('students.studentProfile');
        return view('coach.absensi.show', compact('attendance'));
    }
}
