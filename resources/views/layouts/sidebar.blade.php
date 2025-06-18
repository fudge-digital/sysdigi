<div class="drawer-side">
    <label for="drawer-toggle" class="drawer-overlay"></label>

    <aside class="w-64 bg-base-200 flex flex-col justify-between min-h-screen">
        <div>
            <div class="p-4 text-xl font-bold border-b">
                <div class="flex items-center">
                    <img src="{{ asset('storage/Logo-SS-1.png') }}" width="70" class="mr-2">Admin Panel
                </div>
            </div>
            <ul class="menu p-4 w-full">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                @if(auth()->user()->hasAnyRole(['admin', 'manajemen']))
                <li><a href="{{ route('users.index') }}">Kelola Coach & Siswa</a></li>
                @endif
                <li>
                    <details open>
                        <summary>Absensi</summary>
                        <ul>
                            @if(auth()->user()->hasRole('coach'))
                            <li><a href="{{ route('coach.absensi.index') }}">Riwayat Absensi</a></li>
                            <li><a href="{{ route('coach.absensi.create') }}">Submit Absen</a></li>
                            @endif
                            @if(auth()->user()->hasRole('siswa'))
                            <li><a href="{{ route('siswa.absensi.index') }}">Riwayat Absensi</a></li>
                            @endif
                        </ul>
                    </details>
                </li>
                <li><a href="{{ route('profile.edit') }}">Pengaturan</a></li>
            </ul>
        </div>

        <footer class="p-4 text-sm text-center border-t text-base-content/50">
            <div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
            &copy; {{ date('Y') }} Fudge Digi. All rights reserved.
        </footer>
    </aside>
</div>
