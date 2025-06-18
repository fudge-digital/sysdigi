{{-- Dashboard Siswa --}}
@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="p-4">
    <h2 class="text-2xl font-bold mb-6">Hallo, {{ Auth::user()->name }}</h2>

    {{-- CARD PROFILE --}}
    <div class="card bg-base-100 shadow-md">
        <div class="card-body grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Avatar --}}
            <div class="flex justify-center md:justify-start">
                <div>
                    <div class="w-64 h-64 rounded-md ring ring-primary ring-offset-base-100 ring-offset-2">
                        <img src="{{ asset(
    $user->hasRole('siswa')
        ? $user->studentProfile->photo_profile ?? 'photo_profiles/default-avatar.png'
        : $user->profile->photo_profile ?? 'photo_profiles/default-avatar.png'
) }}" alt="Foto Profil" class="w-full h-100 object-cover">
                    </div>
                </div>
            </div>

            {{-- Info Umum --}}
            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="font-semibold">Nama Lengkap</p>
                    <p>{{ Auth::user()->name }}</p>
                </div>
                <div>
                    <p class="font-semibold">Nama Panggilan</p>
                    <p>{{ Auth::user()->studentProfile?->nama_panggilan ?? '-' }}</p>
                </div>
                <div>
                    <p class="font-semibold">NISS</p>
                    <p>{{ Auth::user()->niss }}</p>
                </div>
                <div>
                    <p class="font-semibold">Email</p>
                    <p>{{ Auth::user()->email }}</p>
                </div>
                <div>
                    <p class="font-semibold">Jenis Kelamin</p>
                    <p>{{ ucfirst(Auth::user()->studentProfile?->jenis_kelamin ?? '-') }}</p>
                </div>
                <div>
                    <p class="font-semibold">Tanggal Lahir</p>
                    <p>{{ \Carbon\Carbon::parse(Auth::user()->studentProfile?->tanggal_lahir)->translatedFormat('d F Y') ?? '-' }}</p>
                </div>
                <div>
                    <p class="font-semibold">Tempat Lahir</p>
                    <p>{{ Auth::user()->studentProfile?->tempat_lahir ?? '-' }}</p>
                </div>
                <div>
                    <p class="font-semibold">Alamat</p>
                    <p>{{ Auth::user()->studentProfile?->alamat ?? '-' }}</p>
                </div>
                <div>
                    <p class="font-semibold">Asal Sekolah</p>
                    <p>{{ Auth::user()->studentProfile?->asal_sekolah ?? '-' }}</p>
                </div>
                <div>
                    <p class="font-semibold">Kategori Umur</p>
                    <span class="badge badge-info badge-outline">{{ Auth::user()->studentProfile?->kategori_umur ?? '-' }}</span>
                </div>
                <div>
                    <p class="font-semibold">Tinggi Badan</p>
                    <p>{{ Auth::user()->studentProfile?->tinggi_badan ? Auth::user()->studentProfile->tinggi_badan . ' cm' : '-' }}</p>
                </div>
                <div>
                    <p class="font-semibold">Berat Badan</p>
                    <p>{{ Auth::user()->studentProfile?->berat_badan ? Auth::user()->studentProfile->berat_badan . ' kg' : '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Riwayat Absen Singkat --}}
    <div class="mt-8">
        <h3 class="text-xl font-semibold mb-4">Riwayat Absensi Terbaru</h3>
        @if($attendances->count())
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis Absen</th>
                            <th>Pelatih</th>
                            <th>Jam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances->take(5) as $attendance)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($attendance->tanggal)->format('d M Y') }}</td>
                                <td>
                                    <span class="badge {{ $attendance->jenis_absen === 'latihan' ? 'badge-primary' : 'badge-accent' }}">
                                        {{ ucfirst($attendance->jenis_absen) }}
                                    </span>
                                </td>
                                <td>{{ $attendance->coach->name ?? '-' }}</td>
                                <td>{{ $attendance->jam }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                <a href="{{ route('siswa.absensi.index') }}" class="btn btn-sm btn-outline">Lihat Semua</a>
            </div>
        @else
            <p class="text-gray-500">Belum ada data absensi.</p>
        @endif
    </div>
</div>
@endsection