@extends('layouts.app')

@section('title', 'Detail Absensi')

@section('content')
<div class="p-4">
    <h2 class="text-xl font-semibold mb-4">Detail Absensi</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div><strong>Jenis Absen:</strong> {{ ucfirst($attendance->jenis_absen) }}</div>
        <div><strong>Tanggal:</strong> {{ $attendance->tanggal }}</div>
        <div><strong>Jam:</strong> {{ $attendance->jam }}</div>

        @if ($attendance->jenis_absen === 'latihan')
            <div><strong>Tempat:</strong> {{ $attendance->tempat_latihan == 'lainnya' ? $attendance->tempat_latihan_lainnya : $attendance->tempat_latihan }}</div>
            <div><strong>Keterangan:</strong> {{ $attendance->keterangan }}</div>
            @if ($attendance->foto_latihan)
                <div class="col-span-2"><img src="{{ asset('storage/' . $attendance->foto_latihan) }}" class="max-w-xs rounded shadow"></div>
            @endif
        @else
            <div><strong>Jenis Pertandingan:</strong> {{ $attendance->jenis_pertandingan }}</div>
            <div><strong>Klub Tanding:</strong> {{ $attendance->klub_tanding }}</div>
            <div><strong>Tempat:</strong> {{ $attendance->tempat_pertandingan }}</div>
            <div><strong>Hasil Skor:</strong> {{ $attendance->hasil_skor }}</div>
            @if ($attendance->foto_pertandingan)
                <div class="col-span-2"><img src="{{ asset('storage/' . $attendance->foto_pertandingan) }}" class="max-w-xs rounded shadow"></div>
            @endif
        @endif
    </div>

    <div class="mt-6">
        <h3 class="text-lg font-bold mb-2">Siswa yang Hadir</h3>
        <ul class="list-disc ml-6">
            @foreach ($attendance->students as $student)
                <li>{{ $student->name }} 
                    @if ($student->studentProfile)
                        ({{ $student->studentProfile->nama_panggilan ?? '-' }} /
                        {{ $student->studentProfile->kategori_umur ?? '-' }} 
                        {{ $student->studentProfile->jenis_kelamin ?? '-' }})
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
