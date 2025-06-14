@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-base-100 text-black-content rounded">
    <h2 class="text-2xl font-bold mb-6">Edit User</h2>

    <form method="POST" enctype="multipart/form-data" action="{{ route('users.update', $user->id) }}" id="userForm">
        @csrf
        @method('PUT')

        {{-- Field dasar --}}
        @include('users.partials.base-fields', ['roles' => $roles, 'user' => $user])

        {{-- Form Tambahan Siswa --}}
        @include('users.partials.form-siswa', ['profile' => $profile ?? null])

        {{-- Form Tambahan Non-Siswa --}}
        @include('users.partials.form-non-siswa', ['profile' => $profile ?? null])

        <div class="mt-6">
            <button type="submit" class="btn btn-neutral w-full">Update</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    @include('users.partials.script')
@endsection
