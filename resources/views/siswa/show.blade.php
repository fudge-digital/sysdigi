@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-base-100 rounded text-black">

    <h2 class="text-2xl font-bold mb-6">Detail Siswa: {{ $user->name }}</h2>

    {{-- Informasi Umum --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <div><span class="font-semibold">NISS:</span> {{ $user->niss }}</div>
        <div><span class="font-semibold">Email:</span> {{ $user->email }}</div>
        <div><span class="font-semibold">Jenis Kelamin:</span> {{ $user->studentProfile->jenis_kelamin }}</div>
        <div><span class="font-semibold">Tanggal Lahir:</span> {{ $user->studentProfile->tanggal_lahir }}</div>
        <div><span class="font-semibold">Kategori Umur:</span> {{ $user->studentProfile->kategori_umur }}</div>
        <div><span class="font-semibold">Nomor Jersey:</span> {{ $user->studentProfile->nomor_jersey }}</div>
    </div>

    {{-- Foto Profil --}}
    <div class="mb-6">
        <p class="font-semibold mb-2">Foto Profil:</p>
        <img src="{{ photoProfileUrl($user) }}"
             class="w-32 h-44 object-cover rounded border" alt="Foto Profil">
    </div>

    {{-- Dokumen --}}
    <h3 class="text-xl font-semibold mb-3">Dokumen Siswa</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach(['kk', 'akta', 'ijazah', 'nisn'] as $jenis)
            @php
                $doc = $user->studentProfile->documents->firstWhere('jenis_dokumen', strtoupper($jenis));
                $url = $doc ? asset($doc->file_path) : null;
            @endphp
            <div class="bg-base-200 p-4 rounded">
                <p class="font-semibold mb-1">{{ strtoupper($jenis) }}</p>
                @if ($doc)
                    <span class="badge badge-success mb-2">Sudah diupload</span><br>
                    <button onclick="previewDokumen('{{ $url }}', '{{ strtoupper($jenis) }}')"
                            class="btn btn-sm btn-outline">
                        Lihat
                    </button>
                    <a href="{{ $url }}" download class="btn btn-sm btn-secondary ml-2">Download</a>
                @else
                    <span class="badge badge-error">Belum ada</span>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Tombol Kembali --}}
    <div class="mt-6">
        <a href="{{ route('users.index') }}" class="btn btn-neutral">← Kembali ke User List</a>
    </div>
</div>

{{-- Modal Dokumen --}}
<dialog id="dokumenModal" class="modal">
    <div class="modal-box w-full max-w-3xl">
        <h3 class="font-bold text-lg mb-4">Dokumen: <span id="modalLabel"></span></h3>
        <img id="modalImage" src="" alt="Preview Dokumen" class="w-full max-h-[500px] object-contain border rounded" />
        <div class="mt-4 text-right">
            <button onclick="dokumenModal.close()" class="btn btn-sm btn-neutral">Tutup</button>
        </div>
    </div>
</dialog>

<script>
    function previewDokumen(url, label) {
        document.getElementById('modalImage').src = url;
        document.getElementById('modalLabel').innerText = label;
        document.getElementById('dokumenModal').showModal();
    }
</script>
@endsection
