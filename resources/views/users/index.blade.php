@extends('layouts.app')

@section('content')
<div class="p-4">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold mb-4">Kelola Pengguna</h1>
        </div>
        <div>
            <a href="{{ route('users.create') }}" class="btn btn-sm btn-success text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                Buat User Baru
            </a>
            <!-- Trigger Button -->
            <button class="btn btn-sm btn-neutral" onclick="my_modal_5.showModal()">Import User</button>

            <!-- Modal -->
            <dialog id="my_modal_5" class="modal modal-bottom sm:modal-middle">
                <div class="modal-box">
                    <h3 class="text-lg font-bold">Import Siswa</h3>

                    {{-- Alert Success --}}
                    @if (session('success'))
                        <div id="import-success-alert" class="alert alert-success mt-2">
                            {{ session('success') }}
                        </div>
                        <script>
                            // Refresh halaman setelah 1.5 detik jika sukses
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        </script>
                    @endif

                    {{-- Alert Error --}}
                    @if (session('error'))
                        <div class="alert alert-error mt-2">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Form Import --}}
                    <form action="{{ route('users.import.siswa') }}" method="POST" enctype="multipart/form-data" class="py-4">
                        @csrf
                        <input type="file" name="file" class="file-input file-input-bordered w-full" required>
                        <button class="btn btn-primary mt-4">Import Siswa</button>
                    </form>

                    <div class="modal-action">
                        <form method="dialog">
                            <button class="btn">Close</button>
                        </form>
                    </div>
                </div>
            </dialog>

        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif

    <form method="GET" class="flex flex-wrap gap-4 items-end mb-6">
        <div>
            <label class="label">Role</label>
            <select name="role" class="select select-bordered">
                <option value="">Semua</option>
                @foreach($roles as $role)
                    <option value="{{ $role }}" @selected(request('role') == $role)>
                        {{ ucfirst($role) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="label">Status</label>
            <select name="status_siswa" class="select select-bordered">
                <option value="">Semua</option>
                <option value="aktif" @selected(request('status_siswa') == 'aktif')>Aktif</option>
                <option value="tidak aktif" @selected(request('status_siswa') == 'tidak aktif')>Tidak Aktif</option>
            </select>
        </div>

        <div>
            <label class="label">Cari</label>
            <input type="text" name="search" class="input input-bordered" value="{{ request('search') }}"
                placeholder="Nama / NISS / No Jersey / Panggilan">
        </div>

        <button class="btn btn-primary">Filter</button>
    </form>

    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $index => $user)
                <tr>
                    <td>{{ $users->firstItem() + $index }}</td>
                    <td>
                    <div class="flex items-center gap-3">
                        <div class="avatar">
                            <div class="skeleton mask mask-circle h-12 w-12">
                                @php
                                    $photo = null;

                                    if (optional($user->studentProfile)->photo_profile) {
                                        $photo = asset('storage/' . $user->studentProfile->photo_profile);
                                    } elseif (optional($user->profile)->photo_profile) {
                                        $photo = asset('storage/' . $user->profile->photo_profile);
                                    } else {
                                        $photo = asset('images/default-avatar.png');
                                    }
                                @endphp
                                <img
                                src="{{ $photo }}"
                                alt="{{ $user->name }}" />
                            </div>
                            </div>
                            <div>
                            <div class="font-bold">{{ $user->name }}</div>
                            <div class="text-sm opacity-50">{{ $user->niss }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->getRoleNames()->join(', ') }}</td>
                    <td>
                        <div class="flex gap-2">
                        @if($user->status === 'aktif')
                            <div class="text-md text-blue">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check-icon lucide-shield-check"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                            </div>
                        @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-x-icon lucide-shield-x"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m14.5 9.5-5 5"/><path d="m9.5 9.5 5 5"/></svg>
                        @endif
                        {{ $user->status ?? '-' }}
                        </div>
                    </td>
                    <td class="flex gap-4 justify-start items-center">
                        <a href="{{ route('users.show', $user) }}" class="btn btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil-icon lucide-pencil"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>
                        </a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-error text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-lg mb-4">
            <div>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error shadow-lg mb-4">
            <div>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
