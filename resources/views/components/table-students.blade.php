<div class="overflow-auto">
<div class="mb-4">
</div>
<table class="table table-zebra w-full">
    <thead>
        <tr class="bg-gray-100 text-black">
            <th>NISS</th>
            <th>Nama</th>
            <th>Nama Panggilan</th>
            <th>WhatsApp</th>
            <th>Tempat & Tgl Lahir</th>
            <th>Kategori Umur</th>
            <th>Nomor Jersey</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($students as $student)
            <tr>
                <td class="px-4 py-2">{{ $student->niss }}</td>
                <td class="px-4 py-2">{{ $student->name }}</td>
                <td class="px-4 py-2">{{ $student->nama_panggilan ?? '-' }}</td>
                <td class="px-4 py-2">
                    @if($student->nomor_whatsapp)
                    <div class="flex justify-start items-center">
                        <button class="btn btn-md bg-white border-white" onclick="window.open('https://wa.me/62{{ $student->nomor_whatsapp ?? '-' }}', '_blank')">
                            <svg xmlns="http://www.w3.org/2000/svg" height="20" width="17.5" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path fill="#008000" d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7 .9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg> +62{{ $student->nomor_whatsapp ?? '-' }}
                        </button>
                    </div>
                    @else
                        -
                    @endif
                </td>
                <td class="px-4 py-2">{{ $student->tempat_lahir ?? '-' }}, {{ $student->tanggal_lahir ?? '-' }}</td>
                <td class="px-4 py-2">{{ $student->kategori_umur ?? '-' }} {{ ucfirst($student->jenis_kelamin ?? '-') }}</td>
                <td class="px-4 py-2">{{ $student->nomor_jersey ?? '-' }}</td>
                <td class="px-4 py-2">{{ ucfirst($student->status_siswa ?? '-') }}</td>
                <td class="px-4 py-2">
                    <!-- Tombol untuk membuka modal -->
                    <button onclick="detail_{{$student->id}}.showModal()" class="btn btn-neutral">Lihat Detail</button>

                    <!-- Modal -->
                    <dialog id="detail_{{ $student->id }}" class="modal">
                        <div class="modal-box w-full max-w-5xl">
                            <div class="flex flex-col md:flex-row gap-6">
                                <!-- FOTO PROFIL -->
                                <div class="md:w-1/3 w-full">
                                    <div class="rounded-lg overflow-hidden shadow-md border aspect-[3/4]">
                                        <img
                                            src="{{ asset($student->studentProfile->photo_profile ?? 'photo_profiles/default-avatar.png') }}"
                                            alt="Foto Siswa"
                                            class="w-full h-full object-cover"
                                        />
                                    </div>
                                    <p class="text-center mt-2 text-lg font-semibold text-gray-700">
                                        #{{ $student->nomor_jersey }}
                                    </p>
                                </div>

                                <!-- DETAIL PROFIL -->
                                <div class="flex-1 space-y-3">
                                    <h2 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-2">
                                        {{ $student->name }}
                                    </h2>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">
                                        <div>
                                            <span class="font-medium">NISS:</span><br>
                                            {{ $student->niss }}
                                        </div>
                                        <div>
                                            <span class="font-medium">Kategori Umur:</span><br>
                                            {{ $student->kategori_umur }} {{ $student->jenis_kelamin }}
                                        </div>
                                        <div>
                                            <span class="font-medium">Tempat Lahir:</span><br>
                                            {{ $student->tempat_lahir }}
                                        </div>
                                        <div>
                                            <span class="font-medium">Tanggal Lahir:</span><br>
                                            {{ \Carbon\Carbon::parse($student->tanggal_lahir)->translatedFormat('d F Y') }}
                                        </div>
                                        <div>
                                            <span class="font-medium">Sekolah Asal:</span><br>
                                            {{ $student->asal_sekolah }}
                                        </div>
                                        <div>
                                            <span class="font-medium">Tinggi Badan:</span><br>
                                            {{ $student->tinggi_badan }} cm
                                        </div>
                                        <div>
                                            <span class="font-medium">Berat Badan:</span><br>
                                            {{ $student->berat_badan }} kg
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Tutup -->
                            <div class="modal-action mt-6">
                                <form method="dialog">
                                    <button class="btn btn-neutral">Tutup</button>
                                </form>
                            </div>
                        </div>
                    </dialog>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="mt-4">
</div>
