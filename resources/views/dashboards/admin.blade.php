@extends('layouts.dashboard')

@section('content')
    <div>
        <div class="header" name="header">
            <h2 class="text-xl font-bold">Dashboard Admin</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">

            <div class="stats bg-base-200 shadow">
                <div class="stat">
                    <div class="stat-title">Total Siswa</div>
                    <div class="stat-value text-primary">{{ $totalSiswa }}</div>
                </div>
            </div>

            <div class="stats bg-green-100 shadow">
                <div class="stat">
                    <div class="stat-title">Siswa Aktif</div>
                    <div class="stat-value text-green-600">{{ $totalAktif }}</div>
                </div>
            </div>

            <div class="stats bg-red-100 shadow">
                <div class="stat">
                    <div class="stat-title">Siswa Tidak Aktif</div>
                    <div class="stat-value text-red-600">{{ $totalNonAktif }}</div>
                </div>
            </div>

        </div>

        <div class="mt-8">
            <h3 class="text-lg font-semibold mb-4">Total Pelatih Berdasarkan Jabatan</h3>

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>Jabatan</th>
                            <th>Jumlah Pelatih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($totalPelatihPerJabatan as $row)
                            <tr>
                                <td>{{ $row->jabatan ?? '-' }}</td>
                                <td>{{ $row->total }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
