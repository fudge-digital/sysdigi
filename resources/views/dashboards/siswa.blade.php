@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="p-4">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h2 class="text-2xl font-bold">Hallo, {{ Auth::user()->name }}</h2>
        <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
            </svg>
            Edit Profil
        </a>
    </div>

    {{-- CARD PROFILE --}}
    <div class="card bg-base-100 shadow-md">
        <div class="card-body flex flex-col md:flex-row gap-6">

            {{-- Foto dan Jersey --}}
            <div class="flex flex-col items-center md:items-start md:w-1/3 w-full">
                <div class="w-64 rounded-md overflow-hidden mx-auto">
                    <img src="{{ asset(
                        $user->hasRole('siswa')
                            ? $user->studentProfile->photo_profile ?? 'photo_profiles/default-avatar.png'
                            : $user->profile->photo_profile ?? 'photo_profiles/default-avatar.png'
                    ) }}" alt="Foto Profil" class="w-full h-full object-cover">
                </div>
                <div class="mx-auto my-4">
                    <span class="text-xl font-bold bg-green-500 text-white py-2 px-4 rounded">
                        {{ Auth::user()->studentProfile?->nomor_jersey }}
                    </span>
                </div>
            </div>

            {{-- Info Umum --}}
            <div class="md:w-2/3 w-full">
                {{-- Untuk mobile gunakan collapsible --}}
                <div class="block md:hidden">
                    <div class="collapse collapse-arrow bg-base-200">
                        <input type="checkbox" />
                        <div class="collapse-title text-lg font-semibold">Informasi Siswa</div>
                        <div class="collapse-content">
                            @include('partials._siswa-info')
                        </div>
                    </div>
                </div>

                {{-- Untuk desktop langsung tampil --}}
                <div class="hidden md:grid md:grid-cols-2 gap-4">
                    @include('partials._siswa-info')
                </div>
            </div>
        </div>
    </div>

    {{-- Riwayat Absen Singkat --}}
    <div class="mt-8">
        <h3 class="text-xl font-semibold mb-4">Riwayat Absensi Terbaru</h3>

        @if($attendances->count())
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full text-sm">
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
