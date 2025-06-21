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

    <form id="hapusDokumenForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Modal untuk preview dokumen -->
    <dialog id="documentModal" class="modal">
        <div class="modal-box max-w-2xl">
            <h3 class="font-bold text-lg mb-4">Dokumen: <span id="modalLabel"></span></h3>
            <img id="modalImage" src="" alt="Preview" class="w-full max-h-[500px] object-contain border rounded" />
            <div class="modal-action">
                <button type="button" class="btn btn-sm btn-neutral" onclick="documentModal.close()">Tutup</button>
            </div>
        </div>
    </dialog>
</div>
@endsection
@push('scripts')
<script>
        function previewDokumen(url, label) {
        document.getElementById('modalImage').src = url;
        document.getElementById('modalLabel').innerText = label;
        documentModal.showModal();
    }

    function hapusDokumen(url, label) {
        if (confirm('Yakin ingin menghapus dokumen ' + label + '?')) {
            const form = document.getElementById('hapusDokumenForm');
            form.setAttribute('action', url);
            form.submit();
        }
    }

    </script>
    @endpush

@section('scripts')
    @include('users.partials.script')
@endsection
