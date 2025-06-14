{{-- resources/views/partials/profile/form-non-siswa.blade.php --}}
<div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
    @if(auth()->user()->hasAnyRole(['admin', 'manajemen']))
        {{-- Jabatan --}}
        <div class="form-control col-span-2 md:col-span-1">
            <label class="label w-full mb-2">Jabatan</label>
            <select name="jabatan" class="select select-neutral w-full">
                <option value="pilih_jabatan" @selected(old('jabatan', $profile->jabatan ?? '') == 'pilih_jabatan')>-- Pilih Jabatan --</option>
                <option value="ketua_umum" @selected(old('jabatan', $profile->jabatan ?? '') == 'ketua_umum')>Ketua Umum</option>
                <option value="wakil_ketua" @selected(old('jabatan', $profile->jabatan ?? '') == 'wakil_ketua')>Wakil Ketua</option>
                <option value="bendahara" @selected(old('jabatan', $profile->jabatan ?? '') == 'bendahara')>Bendahara</option>
                <option value="kepala_pelatih" @selected(old('jabatan', $profile->jabatan ?? '') == 'kepala_pelatih')>Kepala Pelatih</option>
                <option value="staf_operasional" @selected(old('jabatan', $profile->jabatan ?? '') == 'staf_operasional')>Staf Operasional</option>
                <option value="staf_administrasi" @selected(old('jabatan', $profile->jabatan ?? '') == 'staf_administrasi')>Staf Administrasi</option>
                <option value="staf_digitech" @selected(old('jabatan', $profile->jabatan ?? '') == 'staf_digitech')>Staf Digital Technology</option>
                <option value="staf_pelatih" @selected(old('jabatan', $profile->jabatan ?? '') == 'staf_pelatih')>Staf Pelatih</option>
            </select>
        </div>
    @endif
    <input type="hidden" name="jabatan" value="{{ old('jabatan', $profile->jabatan ?? '') }}">

    @if(auth()->user()->hasAnyRole(['admin', 'manajemen', 'coach']))
        {{-- Lisensi --}}
        <div class="form-control col-span-2 md:col-span-1">
            <label class="label w-full mb-2">Lisensi</label>
            <select name="lisensi" class="select select-neutral w-full">
                <option value="belum_berlisensi" @selected(old('lisensi', $profile->lisensi ?? '') == 'belum_berlisensi')>Belum Berlisensi</option>
                <option value="lisensi_a" @selected(old('lisensi', $profile->lisensi ?? '') == 'lisensi_a')>Lisensi A</option>
                <option value="lisensi_b" @selected(old('lisensi', $profile->lisensi ?? '') == 'lisensi_b')>Lisensi B</option>
                <option value="lisensi_c" @selected(old('lisensi', $profile->lisensi ?? '') == 'lisensi_c')>Lisensi C</option>
            </select>
        </div>
    @endif

    @if(auth()->user()->hasAnyRole(['admin', 'manajemen']))
    {{-- Status --}}
    <div class="form-control col-span-2 md:col-span-1">
        <label class="label w-full mb-2">Status</label>
        <select name="status" class="select select-neutral w-full">
            <option value="aktif" {{ old('status', $profile->status ?? 'aktif') == 'aktif' ? 'selected' : '' }}>
                Aktif
            </option>
            <option value="tidak aktif" {{ old('status', $profile->status ?? '') == 'tidak aktif' ? 'selected' : '' }}>
                Tidak Aktif
            </option>
        </select>
    </div>
    @endif

    
        @php
            $existingHandledCategories = $existingHandledCategories ?? []; // fallback aman
            $handledCategories = old('handled_categories', $existingHandledCategories);
        @endphp
        @if ($user->hasRole('coach'))
        <div class="form-control col-span-2 md:col-span-1">
            <label class="label w-full mb-2">Kategori Umur yang dilatih</label>
            <div class="space-y-2 mt-2">
                @foreach ($ageGenderOptions as $option)
                    @php
                        $value = $option->kategori_umur . '|' . $option->jenis_kelamin;
                        $isChecked = in_array($value, $handledCategories);
                    @endphp
                    <label class="flex items-center space-x-2">
                        <input
                            type="checkbox"
                            name="handled_categories[]"
                            value="{{ $value }}"
                            {{ $isChecked ? 'checked' : '' }}
                            {{ $isReadOnly ? 'disabled' : '' }}>
                        <span>
                            {{ $option->kategori_umur }} - {{ ucfirst($option->jenis_kelamin) }}
                            (Total: {{ $option->total_aktif }})
                        </span>
                    </label>
                @endforeach
            </div>
            @if ($isReadOnly && $isChecked)
                <input type="hidden" name="handled_categories[]" value="{{ $value }}">
            @endif
        </div>
        @endif

    {{-- Foto Profil Upload (Tanpa JavaScript Cropper) --}}
    <div class="form-control col-span-2 w-full">
        <label class="label mb-2"><span class="label-text">Foto Profil</span></label>

        <input type="file" name="photo_profile" accept="image/*" class="file-input file-input-bordered w-full" />

        @if($profile->photo_profile)
            <div class="mt-4">
                <img src="{{ asset('storage/' . $profile->photo_profile) }}" class="w-48 h-64 object-cover rounded" />
            </div>
        @endif
    </div>
</div>
