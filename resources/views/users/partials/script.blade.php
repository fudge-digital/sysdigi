@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const roleSelect = document.getElementById('roleSelect');
        const formSiswaSection = document.getElementById('formSiswaSection');
        const formNonSiswaSection = document.getElementById('formNonSiswaSection');

        function toggleForms() {
            const selectedRole = roleSelect.value;

            if (selectedRole === 'siswa') {
                formSiswaSection.classList.remove('hidden');
                formNonSiswaSection.classList.add('hidden');
            } else if (selectedRole === 'coach' || selectedRole === 'manajemen' || selectedRole === 'admin') {
                formSiswaSection.classList.add('hidden');
                formNonSiswaSection.classList.remove('hidden');
            } else {
                formSiswaSection.classList.add('hidden');
                formNonSiswaSection.classList.add('hidden');
            }
        }

        roleSelect.addEventListener('change', toggleForms);
        toggleForms(); // jalankan saat halaman pertama kali dimuat

        // Calculate category from birth year
        function updateKategoriUmur(dateValue) {
            if (!dateValue || !kategoriUmur) return;

            const tahunLahir = new Date(dateValue).getFullYear();
            const tahunSekarang = new Date().getFullYear();
            const usia = tahunSekarang - tahunLahir;

            let kategori = '';
            if (usia < 8) kategori = 'Dibawah U8';
            else if (usia >= 19 && usia <= 24) kategori = 'Pra Divisi';
            else if (usia >= 25 && usia <= 35) kategori = 'Divisi';
            else if (usia > 35) kategori = 'Veteran';
            else kategori = 'U' + usia;

            kategoriUmur.value = kategori;
        }

        // Tanggal lahir change event
        tanggalLahir?.addEventListener('change', function () {
            updateKategoriUmur(this.value);
        });

        // Inisialisasi kategori umur jika tanggal lahir sudah terisi saat load
        if (tanggalLahir?.value) {
            updateKategoriUmur(tanggalLahir.value);
        }

        function togglePassword(id) {
            const field = document.getElementById(id);
            field.type = field.type === 'password' ? 'text' : 'password';
        }
    });
</script>
@endsection

{{-- Tambahkan di bagian bawah file ini --}}