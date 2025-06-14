@extends('layouts.app')

@section('content')
<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Card 1 -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title">Total Pengguna</h2>
                <p>1.230 pengguna terdaftar</p>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title">Aktivitas Hari Ini</h2>
                <p>25 login hari ini</p>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title">Status Sistem</h2>
                <p class="text-success">Online</p>
            </div>
        </div>
    </div>

    <div class="mt-8">
        <div class="hero bg-base-200 rounded-xl p-6">
            <div class="hero-content flex-col lg:flex-row">
                <img src="https://placehold.co/100x100" class="rounded-lg shadow-2xl" />
                <div>
                    <h1 class="text-2xl font-bold">Selamat Datang di Dashboard!</h1>
                    <p class="py-2">Gunakan menu di samping untuk mengelola pengguna, absensi, dan laporan.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
