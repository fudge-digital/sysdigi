@extends('layouts.app')

@section('content')

@if ($errors->any())
    <div class="alert alert-error mb-4">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="max-w-4xl mx-auto p-6 bg-base-100 text-black-content rounded">
    <h2 class="text-2xl font-bold mb-6">Tambah User Baru</h2>

    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" id="userForm">
        @csrf

        {{-- Field dasar (form utama) --}}
        @include('users.partials.base-fields', ['roles' => $roles])

        {{-- Form Tambahan Siswa --}}
        @include('users.partials.form-siswa', ['profile' => null])

        {{-- Form Tambahan Non-Siswa --}}
        @include('users.partials.form-non-siswa', ['profile' => null])

        <div class="mt-6">
            <button type="submit" class="btn btn-neutral">Simpan</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    @include('users.partials.script')
@endsection

