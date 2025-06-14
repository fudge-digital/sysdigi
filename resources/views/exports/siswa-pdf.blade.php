<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Siswa</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 20px;
        }

        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #000;
        }

        .header {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .logo {
            width: 80px;
            margin-right: 20px;
        }

        .institution-info {
            line-height: 1.4;
        }

        .small{
            font-size:10px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #eee;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <img src="{{ public_path('storage/Logo-SS-1.png') }}" alt="Logo" class="logo">
        <div class="institution-info">
            <strong>SATRIA SILIWANGI BASKETBALL</strong><br>
            Jalan Kebon Bibit Barat No.26 RT. 002 RW.010, Tamansari, Bandung Wetan<br>
            WhatsApp: 0895-6064-32020 | Email: info@satriasiliwangibasketball.id
            <p class="small">SS Digi Build Version 01.0625</p>
        </div>
    </div>

    <h2>Data Siswa</h2>

    <table>
        <thead>
            <tr>
                <th>NISS</th>
                <th>Nama Lengkap</th>
                <th>Nama Panggilan</th>
                <th>Photo Profile</th>
                <th>Tempat Lahir</th>
                <th>Tanggal Lahir</th>
                <th>Tinggi Badan</th>
                <th>Berat Badan</th>
                <th>Asal Sekolah</th>
                <th>Kategori Umur</th>
                <th>Nomor WhatsApp</th>
                <th>Nomor Jersey</th>
                <th>Status Siswa</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($siswa as $user)
                <tr>
                    <td>{{ $user->niss }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->studentProfile->nama_panggilan ?? '-' }}</td>
                    <td>
                        @if ($user->studentProfile?->photo_profile)
                            <img src="{{ public_path('storage/photo_profiles' . $user->studentProfile->photo_profile) }}" width="40">
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $user->studentProfile->tempat_lahir ?? '-' }}</td>
                    <td>{{ $user->studentProfile->tanggal_lahir ?? '-' }}</td>
                    <td>{{ $user->studentProfile->tinggi_badan ?? '-' }}</td>
                    <td>{{ $user->studentProfile->berat_badan ?? '-' }}</td>
                    <td>{{ $user->studentProfile->asal_sekolah ?? '-' }}</td>
                    <td>
                        {{ $user->studentProfile->kategori_umur ?? '-' }}
                        {{ $user->studentProfile->jenis_kelamin ?? '-' }}
                    </td>
                    <td>{{ $user->studentProfile->nomor_whatsapp ?? '-' }}</td>
                    <td>{{ $user->studentProfile->nomor_jersey ?? '-' }}</td>
                    <td>{{ $user->studentProfile->status_siswa ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="15" class="text-center">Tidak ada data siswa.</td></tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>