@extends('layouts.app')

@section('title', 'Riwayat Absensi')

@section('content')
    <x-toast />

    <div class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Riwayat Absensi (Mulai Hari Ini)</h2>
        </div>

        <div class="overflow-x-auto shadow rounded-lg">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-base-200 text-base-content">
                        <th class="whitespace-nowrap">Tanggal</th>
                        <th>Jenis</th>
                        <th>Jumlah Siswa</th>
                        <th>Kategori Umur</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attendances as $absen)
                        @if($absen->tanggal >= today()->toDateString())
                            <tr>
                                <td class="whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('d F Y') }}
                                </td>
                                <td>
                                    <span class="badge badge-outline badge-{{ $absen->jenis_absen === 'latihan' ? 'success' : 'info' }}">
                                        {{ ucfirst($absen->jenis_absen) }}
                                    </span>
                                </td>
                                <td>{{ $absen->students->count() }}</td>
                                <td>
                                    @php
                                        $kategoriList = $absen->students
                                            ->pluck('studentProfile.kategori_umur')
                                            ->filter()
                                            ->unique()
                                            ->sort()
                                            ->values();
                                    @endphp
                                    @if($kategoriList->isNotEmpty())
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($kategoriList as $kategori)
                                                <span class="badge badge-sm badge-ghost">{{ $kategori }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">Tidak ada data</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('coach.absensi.show', $absen->id) }}" class="btn btn-sm btn-info">Detail</a>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-400 italic">Belum ada absensi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $attendances->links() }}
        </div>
    </div>
@endsection
