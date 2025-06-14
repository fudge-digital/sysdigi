{{-- resources/views/dashboards/coach.blade.php --}}
@extends('layouts.dashboard')

@section('content')
    <div class="flex items-center justify-between mb-2">
        <h2 class="text-2xl font-bold">Selamat datang Coach {{ auth()->user()->name }}!</h2>
        <div class="dropdown dropdown-end hidden md:block">
            <div tabindex="0" role="button" class="btn btn-neutral">Export Data Siswa</div>
            <ul tabindex="0" class="dropdown-content menu bg-black rounded-sm mt-1 z-1 w-52 p-2 shadow-sm">
                @php
                    $currentQuery = request()->query(); // Ambil query dari URL
                @endphp
                <li><a href="{{ url('/users/export-siswa') . '?' . http_build_query(array_merge($currentQuery, ['format' => 'pdf'])) }}" class="text-white">Export PDF</a></li>
            </ul>
        </div>
    </div>
    <div class="mb-4">
        <p>
            Berikut adalah kategori umur yang di assign untuk anda:
            @foreach ($kategoriUmurOptions as $kategori)
                <span>{{ $kategori }}</span>
                @if (!$loop->last)
                    <span class="text-gray-600 text-bold">, </span>
                @endif
            @endforeach
        </p>
    </div>
    <div class="mt-6">
        <div class="grid grid-cols-2 gap-4 my-4">
            <div class="p-4 bg-green-100 rounded shadow">
                <p class="text-sm">Siswa Aktif</p>
                <h3 class="text-3xl font-bold text-green-700">{{ $totalAktif }}</h3>
            </div>
            <div class="p-4 bg-red-100 rounded shadow">
                <p class="text-sm">Siswa Tidak Aktif</p>
                <h3 class="text-3xl font-bold text-red-700">{{ $totalNonAktif }}</h3>
            </div>
        </div>

        @if($students->isEmpty())
            <p class="text-gray-600 text-sm text-black">Tidak ada data siswa pada pilihan ini. <a class="btn btn-neutral btn-sm" href="{{ route('dashboard') }}">Kembali</a></p>
        @else
            <form method="GET" action="{{ route('coach.dashboard') }}" class="mb-4 flex flex-wrap gap-4 items-end">
                <div class="grids grid-cols-1 sm:grid-cols-2 gap-4 w-full md:w-48">
                    <label for="kategori_umur" class="label-text">Kategori Umur</label>
                    <select name="kategori_umur" id="kategori_umur" class="select select-bordered w-full" onchange="this.form.submit()">
                        <option value="">Semua</option>
                        @foreach ($kategoriUmurOptions as $kategori)
                            <option value="{{ $kategori }}" @selected(request('kategori_umur') == $kategori)>
                                {{ $kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grids grid-cols-1 sm:grid-cols-2 gap-4 w-full md:w-48">
                    <label for="tahun_lahir" class="label-text">Tahun Lahir</label>
                    <select name="tahun_lahir" id="tahun_lahir" class="select select-bordered w-full" onchange="this.form.submit()">
                        <option value="">Semua</option>
                        @foreach ($tahunOptions as $tahun)
                            <option value="{{ $tahun }}" @selected(request('tahun_lahir') == $tahun)>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grids grid-cols-1 sm:grid-cols-2 gap-4 w-full md:w-48">
                    <label for="search" class="label-text">Cari Nama / No Jersey</label>
                    <input type="text" name="search" id="search" class="input input-bordered w-full" value="{{ request('search') }}">
                </div>

                <div class="grids grid-cols-1 sm:grid-cols-2 gap-4 w-full md:w-48">
                    <button type="submit" class="btn btn-primary mt-1">Filter</button>
                </div>

                <div class="dropdown dropdown-end block md:hidden">
                    <div tabindex="0" role="button" class="btn btn-neutral">Export Data Siswa</div>
                    <ul tabindex="0" class="dropdown-content menu bg-black rounded-sm mt-1 z-1 w-52 p-2 shadow-sm">
                        @php
                            $currentQuery = request()->query(); // Ambil query dari URL
                        @endphp
                        <li><a href="{{ url('/users/export-siswa') . '?' . http_build_query(array_merge($currentQuery, ['format' => 'pdf'])) }}" class="text-white">Export PDF</a></li>
                    </ul>
                </div>
            </form>
            

            <div role="tablist" class="tabs tabs-bordered mb-4">
                <a role="tab"
                class="tab {{ $status === 'aktif' ? 'tab-active' : '' }}"
                href="{{ route('coach.dashboard', ['status' => 'aktif'] + request()->except('page')) }}">
                    Siswa Aktif
                </a>
                <a role="tab"
                class="tab {{ $status !== 'aktif' ? 'tab-active' : '' }}"
                href="{{ route('coach.dashboard', ['status' => 'nonaktif'] + request()->except('page')) }}">
                    Siswa Tidak Aktif
                </a>
            </div>
            @include('components.table-students', ['students' => $students])
            <div class="mt-4">
                {{ $students->links() }}
            </div>
        @endif
    </div>
@endsection
