<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Daftar Siswa yang Ditangani</h2>
    </x-slot>

    <div class="mt-4 overflow-x-auto">
        <table class="table w-full">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Kategori Umur</th>
                    <th>Jenis Kelamin</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $i => $student)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->kategori_umur }}</td>
                        <td>{{ ucfirst($student->jenis_kelamin) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada siswa yang ditugaskan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
