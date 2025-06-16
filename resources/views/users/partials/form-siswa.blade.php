<div id="formSiswaSection" class="mt-6 hidden p-4 bg-gray-100 rounded grid grid-cols-1 md:grid-cols-2 gap-4">
    {{-- Foto Profil Upload (Tanpa JavaScript Cropper) --}}
    <div class="form-control col-span-2 w-full">
        <label class="label mb-2"><span class="label-text">Foto Profil</span></label>

        <input type="file" name="photo_profile" accept="image/*" class="file-input file-input-bordered w-full" />
            <div class="mt-4">
                <img src="{{ photoProfileUrl($user ?? null) }}" class="w-48 h-64 object-cover rounded" />
            </div>
    </div>
    
    {{-- Nama Panggilan --}}
    <div class="form-control col-span-2 md:col-span-1 w-full">
        <label class="label text-black text-bold mb-2 w-full">Nama Panggilan</label>
        <input type="text" name="nama_panggilan" class="text-input text-input-neutral w-full"
            value="{{ old('nama_panggilan', $profile->nama_panggilan ?? '') }}">
    </div>

    {{-- Tempat Lahir --}}
    <div class="form-control col-span-2 md:col-span-1 w-full">
        <label class="label text-black text-bold mb-2 w-full">Tempat Lahir</label>
        <input type="text" name="tempat_lahir" class="text-input text-input-neutral w-full"
            value="{{ old('tempat_lahir', $profile->tempat_lahir ?? '') }}">
    </div>

    {{-- Tanggal Lahir --}}
    <div class="form-control col-span-2 md:col-span-1 w-full">
        <label class="label text-black text-bold mb-2 w-full">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" id="tanggalLahir" class="text-input text-input-neutral w-full"
            value="{{ old('tanggal_lahir', $profile->tanggal_lahir ?? '') }}">
    </div>

    {{-- Kategori Umur --}}
    <div class="form-control col-span-2 md:col-span-1 w-full">
        <label class="label text-black text-bold mb-2 w-full">Kategori Umur</label>
        <input type="text" name="kategori_umur" id="kategoriUmur" class="text-input text-input-neutral w-full border-gray-200 read-only:bg-gray-200"
            readonly value="{{ old('kategori_umur', $profile->kategori_umur ?? '') }}">
    </div>

    {{-- Tinggi Badan --}}
    <div class="form-control col-span-2 md:col-span-1 w-full">
        <label class="label text-black text-bold mb-2 w-full">Tinggi Badan (cm)</label>
        <input type="number" name="tinggi_badan" class="text-input text-input-neutral w-full"
            value="{{ old('tinggi_badan', $profile->tinggi_badan ?? '') }}">
    </div>

    {{-- Berat Badan --}}
    <div class="form-control col-span-2 md:col-span-1 w-full">
        <label class="label text-black text-bold mb-2 w-full">Berat Badan (kg)</label>
        <input type="number" name="berat_badan" class="text-input text-input-neutral w-full"
            value="{{ old('berat_badan', $profile->berat_badan ?? '') }}">
    </div>

    {{-- Asal Sekolah --}}
    <div class="form-control col-span-2 md:col-span-1 w-full">
        <label class="label text-black text-bold mb-2 w-full">Asal Sekolah</label>
        <input type="text" name="asal_sekolah" class="text-input text-input-neutral w-full"
            value="{{ old('asal_sekolah', $profile->asal_sekolah ?? '') }}">
    </div>

    {{-- Nomor WhatsApp --}}
    <div class="form-control col-span-2 md:col-span-1 w-full">
        <label class="label text-black text-bold mb-2 w-full">Nomor WhatsApp</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center p-3 text-black text-bold border-black bg-gray-200">62</span>
            <input
                type="tel"
                name="nomor_whatsapp"
                id="nomor_whatsapp_input"
                class="text-input text-input-neutral w-full pl-12"
                placeholder=""
                value="{{ old('nomor_whatsapp', $profile->nomor_whatsapp ? ltrim($profile->nomor_whatsapp, '62') : '') }}"
            >
        </div>
        <div class="flex items-center pr-3 text-gray-500 text-sm">
                Masukkan tanpa angka 0 (Contoh: 81234567890)</div>
    </div>

    {{-- Nomor Jersey --}}
    <div class="form-control col-span-2 md:col-span-1 w-full">
        <label class="label text-black text-bold mb-2 w-full">Nomor Jersey</label>
        <input type="text" name="nomor_jersey" class="text-input text-input-neutral w-full"
            value="{{ old('nomor_jersey', $profile->nomor_jersey ?? '') }}">
    </div>

    {{-- Status Siswa --}}
    <div class="form-control col-span-2 md:col-span-1 w-full">
        <label class="label text-black text-bold mb-2 w-full">Status Siswa</label>
        <select name="status_siswa" class="select select-neutral w-full">
            <option value="aktif" {{ old('status_siswa', optional($profile)->status_siswa) === 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="tidak aktif" {{ old('status_siswa', optional($profile)->status_siswa) === 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
        </select>
    </div>

    {{-- Jenis Kelamin --}}
    <div class="form-control col-span-2 md:col-span-1 w-full">
        <label class="label text-black text-bold mb-2 w-full">Jenis Kelamin</label>
        <select name="jenis_kelamin" class="select select-neutral w-full">
            <option value="putra" @selected(old('jenis_kelamin', $profile->jenis_kelamin ?? '') == 'putra')>Putra</option>
            <option value="putri" @selected(old('jenis_kelamin', $profile->jenis_kelamin ?? '') == 'putri')>Putri</option>
        </select>
    </div>

    {{-- Alamat --}}
    <div class="form-control col-span-1 md:col-span-2 w-full">
        <label class="label text-black text-bold mb-2 w-full">Alamat</label>
        <textarea name="alamat" class="textarea textarea-neutral w-full">{{ old('alamat', $profile->alamat ?? '') }}</textarea>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('nomor_whatsapp_input');

        input.addEventListener('input', function () {
            let val = input.value;

            // Hilangkan semua karakter non-digit
            val = val.replace(/\D/g, '');

            // Hapus awalan +62, 62, atau 0 jika ada
            if (val.startsWith('62')) {
                val = val.substring(2);
            } else if (val.startsWith('0')) {
                val = val.substring(1);
            }

            input.value = val;
        });
    });
</script>