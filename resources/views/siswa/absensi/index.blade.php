@extends('layouts.app')

@section('title', 'Riwayat Kehadiran Saya')

@section('content')
<div class="p-4">
    <h2 class="text-lg font-semibold mb-4">Riwayat Kehadiran Saya</h2>

    @if ($attendances->count())
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Pelatih</th>
                        <th>Detail</th>
                        <th>Foto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendances as $attendance)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($attendance->tanggal)->translatedFormat('d F Y') }}</td>
                            <td>{{ ucfirst($attendance->jenis_absen) }}</td>
                            <td>{{ $attendance->coach->name ?? '-' }}</td>
                            <td class="text-sm">
                                @if($attendance->jenis_absen === 'latihan')
                                    Tempat: {{ $attendance->tempat_latihan === 'lainnya' ? $attendance->tempat_latihan_lainnya : $attendance->tempat_latihan }}<br>
                                    Jam: {{ $attendance->jam }}<br>
                                    @if($attendance->keterangan)
                                        Keterangan: {{ $attendance->keterangan }}
                                    @endif
                                @elseif($attendance->jenis_absen === 'pertandingan')
                                    Jenis: {{ ucfirst($attendance->jenis_pertandingan) }}<br>
                                    @if($attendance->jenis_pertandingan === 'turnamen')
                                        Turnamen: {{ $attendance->nama_turnamen }}<br>
                                    @endif
                                    Klub: {{ $attendance->klub_tanding }}<br>
                                    Tempat: {{ $attendance->tempat_pertandingan }}<br>
                                    Skor: {{ $attendance->hasil_skor }}
                                @endif
                            </td>
                            <td>
                                @if($attendance->jenis_absen === 'latihan' && $attendance->foto_latihan)
                                    <img src="{{ asset('storage/' . $attendance->foto_latihan) }}" class="w-20 rounded shadow">
                                @elseif($attendance->jenis_absen === 'pertandingan' && $attendance->foto_pertandingan)
                                    <img src="{{ asset('storage/' . $attendance->foto_pertandingan) }}" class="w-20 rounded shadow">
                                @else
                                    <span class="text-xs text-gray-500">Tidak ada</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $attendances->links() }}
        </div>
    @else
        <div class="text-center text-gray-600 mt-8">
            Belum ada data kehadiran.
        </div>
    @endif
</div>
@endsection
