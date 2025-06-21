<div class="mb-2 md:mb-2">
    <p class="font-semibold">Nama Lengkap</p>
    <p>{{ Auth::user()->name }}</p>
</div>
<div class="mb-2 md:mb-2">
    <p class="font-semibold">Nama Panggilan</p>
    <p>{{ Auth::user()->studentProfile?->nama_panggilan ?? '-' }}</p>
</div>
<div class="mb-2 md:mb-2">
    <p class="font-semibold">NISS</p>
    <p>{{ Auth::user()->niss }}</p>
</div>
<div class="mb-2 md:mb-2">
    <p class="font-semibold">Email</p>
    <p>{{ Auth::user()->email }}</p>
</div>
<div class="mb-2 md:mb-2">
    <p class="font-semibold">Tanggal Lahir</p>
    <p>{{ Auth::user()->studentProfile?->tanggal_lahir ? \Carbon\Carbon::parse(Auth::user()->studentProfile->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</p>
</div>
<div class="mb-2 md:mb-2">
    <p class="font-semibold">Tempat Lahir</p>
    <p>{{ Auth::user()->studentProfile?->tempat_lahir ?? '-' }}</p>
</div>
<div class="md:col-span-2 mb-2 md:mb-2">
    <p class="font-semibold">Alamat</p>
    <p>{{ Auth::user()->studentProfile?->alamat ?? '-' }}</p>
</div>
<div class="mb-2 md:mb-2">
    <p class="font-semibold">Asal Sekolah</p>
    <p>{{ Auth::user()->studentProfile?->asal_sekolah ?? '-' }}</p>
</div>
<div class="mb-2 md:mb-2">
    <p class="font-semibold">Kategori Umur</p>
    <p>
        <span class="badge badge-info text-white">
            {{ Auth::user()->studentProfile?->kategori_umur ?? '-' }}
            {{ ucfirst(Auth::user()->studentProfile?->jenis_kelamin ?? '-') }}
        </span>
    </p>
</div>
<div class="mb-2 md:mb-2">
    <p class="font-semibold">Tinggi Badan</p>
    <p>{{ Auth::user()->studentProfile?->tinggi_badan ? Auth::user()->studentProfile->tinggi_badan . ' cm' : '-' }}</p>
</div>
<div class="mb-2 md:mb-2">
    <p class="font-semibold">Berat Badan</p>
    <p>{{ Auth::user()->studentProfile?->berat_badan ? Auth::user()->studentProfile->berat_badan . ' kg' : '-' }}</p>
</div>
<div class="md:col-span-2">
    <p class="font-semibold mb-2">Dokumen</p>
    <div class="flex flex-wrap gap-2">
        @php $student = auth()->user()->studentProfile; @endphp
        @foreach(['kk', 'akta', 'ijazah', 'nisn'] as $jenis)
            @if ($student && $student->documents->where('jenis_dokumen', strtoupper($jenis))->count())
                <span class="badge badge-success text-white p-4">
                    <svg class="size-[1em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g fill="currentColor" stroke-linejoin="miter" stroke-linecap="butt"><circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-linecap="square" stroke-miterlimit="10" stroke-width="2"></circle><polyline points="7 13 10 16 17 8" fill="none" stroke="currentColor" stroke-linecap="square" stroke-miterlimit="10" stroke-width="2"></polyline></g></svg>
                    {{ strtoupper($jenis) }} sudah ada</span>
            @else
                <span class="badge badge-error text-white p-4">
                    <svg class="size-[1em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g fill="currentColor"><rect x="1.972" y="11" width="20.056" height="2" transform="translate(-4.971 12) rotate(-45)" fill="currentColor" stroke-width="0"></rect><path d="m12,23c-6.065,0-11-4.935-11-11S5.935,1,12,1s11,4.935,11,11-4.935,11-11,11Zm0-20C7.038,3,3,7.037,3,12s4.038,9,9,9,9-4.037,9-9S16.962,3,12,3Z" stroke-width="0" fill="currentColor"></path></g></svg>
                    {{ strtoupper($jenis) }} belum ada</span>
            @endif
        @endforeach
    </div>
</div>
