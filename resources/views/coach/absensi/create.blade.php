@extends('layouts.app')

@section('title', 'Buat Absensi')

@section('content')
<div class="p-4">
    <x-toast />
    @if($errors->any())
    <div class="alert alert-error mb-4">
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form method="POST" action="{{ route('coach.absensi.store') }}" enctype="multipart/form-data" x-data="{
    jenis: '{{ old('jenis_absen', 'latihan') }}',
    tempat: '{{ old('tempat_latihan') }}',
    jenisPertandingan: '{{ old('jenis_pertandingan', 'scrimmage') }}'
}">
        @csrf

        <!-- Jenis Absen -->
        <div class="form-control">
            <label class="label">Jenis Absen</label>
            <select name="jenis_absen" x-model="jenis" class="select select-bordered w-full">
                <option value="latihan">Latihan</option>
                <option value="pertandingan">Pertandingan</option>
            </select>
        </div>

        <!-- Tanggal & Jam -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div class="form-control">
                <label class="label">Tanggal</label>
                <input type="date" name="tanggal" class="input input-bordered w-full" value="{{ now()->toDateString() }}">
            </div>
            <div class="form-control">
                <label class="label">Jam</label>
                <input type="time" name="jam" class="input input-bordered w-full">
            </div>
        </div>

        <!-- Latihan Fields -->
        <div x-show="jenis === 'latihan'" class="mt-4 space-y-4">
            <div class="form-control">
                <label class="label">Tempat Latihan</label>
                <select name="tempat_latihan" x-model="tempat" class="select select-bordered w-full">
                    <option value="seskoad1">GOR Seskoad Court 1</option>
                    <option value="seskoad2">GOR Seskoad Court 2</option>
                    <option value="lainnya">GOR Lainnya</option>
                </select>
            </div>
            <div class="form-control" x-show="tempat === 'lainnya'">
                <label class="label">Tempat Lainnya</label>
                <input type="text" name="tempat_latihan_lainnya" class="input input-bordered w-full">
            </div>
            <div class="form-control">
                <label class="label">Foto Latihan</label>
                <input type="file" name="foto_latihan" class="file-input file-input-bordered w-full">
            </div>
            <div class="form-control">
                <label class="label">Keterangan Lain</label>
                <textarea name="keterangan" class="textarea textarea-bordered w-full"></textarea>
            </div>
        </div>

        <!-- Pertandingan Fields -->
        <div x-show="jenis === 'pertandingan'" class="mt-4 space-y-4">
            <div class="form-control">
                <label class="label">Jenis Pertandingan</label>
                <select name="jenis_pertandingan" x-model="jenisPertandingan" class="select select-bordered w-full">
                    <option value="scrimmage">Scrimmage</option>
                    <option value="turnamen">Turnamen</option>
                </select>
            </div>
            <div class="form-control" x-show="jenisPertandingan === 'turnamen'">
                <label class="label">Nama Turnamen</label>
                <input type="text" name="nama_turnamen" class="input input-bordered w-full">
            </div>
            <div class="form-control">
                <label class="label">Nama Klub Tanding</label>
                <input type="text" name="klub_tanding" class="input input-bordered w-full">
            </div>
            <div class="form-control">
                <label class="label">Tempat Pertandingan</label>
                <input type="text" name="tempat_pertandingan" class="input input-bordered w-full">
            </div>
            <div class="form-control">
                <label class="label">Hasil Skor</label>
                <input type="text" name="hasil_skor" class="input input-bordered w-full">
            </div>
            <div class="form-control">
                <label class="label">Foto Pertandingan</label>
                <input type="file" name="foto_pertandingan" class="file-input file-input-bordered w-full">
            </div>
        </div>

        <!-- Siswa Hadir Grouped by Kategori -->
        <div class="my-6">
            <label class="label">Cari Siswa Hadir</label>
            <input type="text" id="search-q" class="input input-bordered w-full mb-4" placeholder="Cari nama atau nama panggilan...">

            <div class="space-y-4">
                @php $grouped = $students->groupBy(fn($s) => $s->studentProfile->kategori_umur ?? 'Lainnya'); @endphp
                @foreach($grouped as $kategori => $group)
                <div class="border p-2 rounded">
                    <h3 class="font-semibold text-sm text-gray-600 mb-2">{{ $kategori }}</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                        @foreach($group as $student)
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="siswa_hadir[]" value="{{ $student->id }}" class="checkbox">
                                {{ $student->name }}
                                @if($student->studentProfile)
                                    ({{ $student->studentProfile->nama_panggilan ?? '-' }} /
                                    {{ $student->studentProfile->jenis_kelamin ?? '-' }})
                                @endif
                            </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <input type="hidden" name="selected_siswa_hadir" id="selected_siswa_hadir">

        <div class="mt-6">
            <button type="submit" class="btn btn-primary w-full">Simpan Absensi</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const storageKey = 'selectedSiswaHadir';
    const selected = new Set(JSON.parse(localStorage.getItem(storageKey) || '[]'));

    // Load checked checkbox dari localStorage
    selected.forEach(id => {
        const checkbox = document.querySelector(`input[name="siswa_hadir[]"][value="${id}"]`);
        if (checkbox) checkbox.checked = true;
    });

    // Update localStorage saat checkbox berubah
    document.querySelectorAll('input[name="siswa_hadir[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const id = this.value;
            if (this.checked) {
                selected.add(id);
            } else {
                selected.delete(id);
            }
            localStorage.setItem(storageKey, JSON.stringify(Array.from(selected)));
        });
    });

    // Handle form submit
    const form = document.querySelector('form');
    form.addEventListener('submit', function () {
        document.getElementById('selected_siswa_hadir').value = Array.from(selected).join(',');

        // clear localStorage jika tidak error
        setTimeout(() => {
            if (!document.querySelector('.toast-error')) {
                localStorage.removeItem(storageKey);
            }
        }, 100);
    });

    // Fitur pencarian
    const input = document.getElementById('search-q');
    if (input) {
        input.addEventListener('input', function () {
            const keyword = this.value.toLowerCase();
            document.querySelectorAll('input[name="siswa_hadir[]"]').forEach(cb => {
                const label = cb.closest('label');
                const text = label.textContent.toLowerCase();
                label.style.display = text.includes(keyword) ? '' : 'none';
            });
        });
    }
});
</script>
@endpush
