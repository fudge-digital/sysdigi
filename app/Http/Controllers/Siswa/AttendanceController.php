<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = auth()->user()
            ->studentAttendances()
            ->with(['coach'])
            ->orderByDesc('tanggal')
            ->paginate(10);

        return view('siswa.absensi.index', compact('attendances'));
    }
}
