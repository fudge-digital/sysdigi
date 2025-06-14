@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-base-100 text-black-content rounded">
    <h2 class="text-2xl font-bold mb-6">Edit Profil</h2>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Base fields (name, email, password, dll) --}}
        @include('profile.partials.base-fields')

        {{-- Partial berdasarkan role --}}
        @if ($user->hasRole('siswa'))
            @include('profile.partials.form-siswa')
        @else
            @include('profile.partials.form-non-siswa')
        @endif

        <div class="mt-6">
            <button class="btn btn-neutral w-full">Simpan</button>
        </div>
    </form>

</div>
@endsection