@extends('layouts.app')

@section('title', 'Detail Absensi')

@section('content')
<div class="p-4">
    <a href="{{ route('coach.absensi.index') }}" class="btn btn-sm btn-outline mb-4">← Kembali</a>
    <div class="card bg-base-100 shadow-md">
        <div class="card-body grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="font-semibold">Jenis Absen</p>
                <span class="badge badge-info badge-outline">{{ ucfirst($attendance->jenis_absen) }}</span>
            </div>
            <div>
                <p class="font-semibold">Tanggal</p>
                <span>{{ \Carbon\Carbon::parse($attendance->tanggal)->translatedFormat('d F Y') }}</span>
            </div>
            <div>
                <p class="font-semibold">Jam</p>
                <span>{{ $attendance->jam }}</span>
            </div>
            <div>
                <p class="font-semibold">Pelatih</p>
                <span>{{ $attendance->coach->name ?? '-' }}</span>
            </div>

            @if ($attendance->jenis_absen === 'latihan')
                <div>
                    <p class="font-semibold">Tempat Latihan</p>
                    <span>
                        {{ $attendance->tempat_latihan === 'lainnya' 
                            ? $attendance->tempat_latihan_lainnya 
                            : Str::title(str_replace('_', ' ', $attendance->tempat_latihan)) }}
                    </span>
                </div>
                @if ($attendance->keterangan)
                    <div>
                        <p class="font-semibold">Keterangan</p>
                        <span>{{ $attendance->keterangan }}</span>
                    </div>
                @endif
                @if ($attendance->foto_latihan)
                    <div class="md:col-span-2">
                        <p class="font-semibold mb-1">Foto Latihan</p>
                        <img src="{{ asset('storage/' . $attendance->foto_latihan) }}" class="rounded shadow-md w-full max-w-md">
                    </div>
                @endif
            @else
                <div>
                    <p class="font-semibold">Jenis Pertandingan</p>
                    <span class="badge badge-success badge-outline">{{ ucfirst($attendance->jenis_pertandingan) }}</span>
                </div>
                @if ($attendance->jenis_pertandingan === 'turnamen')
                    <div>
                        <p class="font-semibold">Nama Turnamen</p>
                        <span>{{ $attendance->nama_turnamen }}</span>
                    </div>
                @endif
                <div>
                    <p class="font-semibold">Klub Tanding</p>
                    <span>{{ $attendance->klub_tanding }}</span>
                </div>
                <div>
                    <p class="font-semibold">Tempat Pertandingan</p>
                    <span>{{ $attendance->tempat_pertandingan }}</span>
                </div>
                <div>
                    <p class="font-semibold">Hasil Skor</p>
                    <span>{{ $attendance->hasil_skor }}</span>
                </div>
                @if ($attendance->foto_pertandingan)
                    <div class="md:col-span-2">
                        <p class="font-semibold mb-1">Foto Pertandingan</p>
                        <img src="{{ asset('storage/' . $attendance->foto_pertandingan) }}" class="rounded shadow-md w-full max-w-md">
                    </div>
                @endif
            @endif
        </div>
    </div>

    <div class="mt-6 card bg-base-100 shadow-sm">
        <div class="card-body">
            <h3 class="text-lg font-semibold mb-2">Siswa yang Hadir ({{ $attendance->students->count() }})</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                @foreach ($attendance->students as $student)
                    <div class="border p-2 rounded">
                        <p class="font-medium">{{ $student->name }}</p>
                        @if ($student->studentProfile)
                            <p class="text-sm text-gray-500">
                                {{ $student->studentProfile->nama_panggilan ?? '-' }} /
                                {{ $student->studentProfile->kategori_umur ?? '-' }} /
                                {{ ucfirst($student->studentProfile->jenis_kelamin ?? '-') }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
