<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    {{-- Nama Lengkap --}}
    <div class="form-control w-full">
        <label for="name" class="label text-sm text-bold text-black mb-2 w-full"><span class="label-text">Nama Lengkap</span></label>
        <input type="text" name="name" id="name"
            class="text-input text-input-neutral w-full"
            value="{{ old('name', $user->name ?? '') }}">
        @error('name')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    {{-- NISS --}}
    <div class="form-control w-full">
        <label for="niss" class="label text-sm text-bold text-black mb-2 w-full"><span class="label-text">NISS (Nomor Induk Siswa)</span></label>
        @if (!auth()->user()->hasRole('manajemen') && !auth()->user()->hasRole('coach') && !auth()->user()->hasRole('siswa'))
            <input type="text" name="niss" id="niss"
                class="text-input text-input-neutral w-full"
                value="{{ old('niss', $user->niss ?? '') }}">
            @error('niss')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
            @else
            <input type="text" name="niss" id="niss"
                class="text-input text-input-neutral w-full border-gray-200 read-only:bg-gray-200"
                value="{{ old('niss', $user->niss ?? '') }}" readonly>
        @endif
    </div>

    {{-- Email --}}
    <div class="form-control w-full">
        <label for="email" class="label text-sm text-bold text-black mb-2 w-full"><span class="label-text">Email</span></label>
        <input type="email" name="email" id="email"
            class="text-input text-input-neutral w-full"
            value="{{ old('email', $user->email ?? '') }}">
        @error('email')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    @php
        $isEditingSelf = auth()->user()->id === ($user->id ?? null);
        $isAdmin = auth()->user()->hasRole('admin');
    @endphp

    @if (!isset($user) || request()->routeIs('users.edit'))
        {{-- Jika user sedang mengedit dirinya sendiri --}}
        @if($isEditingSelf && !$isAdmin)
            {{-- Current Password --}}
            <div class="form-control w-full">
                <label for="current_password" class="label"><span class="label-text">Password Saat Ini</span></label>
                <input type="password" name="current_password" id="current_password" class="input input-bordered w-full">
                @error('current_password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        @endif

        {{-- Password Baru --}}
        <div class="form-control w-full">
            <label for="password" class="label"><span class="label-text">Password Baru</span></label>
            <div class="relative">
                <input type="password" name="password" id="password" class="input input-bordered w-full pr-10">
                <span class="absolute right-3 top-3 cursor-pointer" onclick="togglePassword('password')">👁️</span>
            </div>
            @error('password')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div class="form-control w-full">
            <label for="password_confirmation" class="label"><span class="label-text">Konfirmasi Password Baru</span></label>
            <div class="relative">
                <input type="password" name="password_confirmation" id="password_confirmation" class="input input-bordered w-full pr-10">
                <span class="absolute right-3 top-3 cursor-pointer" onclick="togglePassword('password_confirmation')">👁️</span>
            </div>
        </div>
    @endif

    {{-- Role (Hanya Admin yang bisa ubah) --}}
    @role('admin')
    <div class="form-control w-full">
        <label for="roleSelect" class="label text-sm text-bold text-black mb-2 w-full"><span class="label-text">Role</span></label>
        <select name="role" id="roleSelect" class="select select-neutral w-full">
            <option value="">Pilih Role</option>
            @foreach ($roles as $role)
                <option value="{{ $role->name }}" {{ old('role', isset($user) ? $user->getRoleNames()->first() : '') == $role->name ? 'selected' : '' }}>
                    {{ ucfirst($role->name) }}
                </option>
            @endforeach
        </select>
        @error('role')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>
    @else
        {{-- Untuk Manajemen, tampilkan role sebagai text readonly --}}
        <div class="form-control w-full">
            <label class="label text-sm text-bold text-black mb-2 w-full"><span class="label-text">Role</span></label>
            <input type="text" class="input input-bordered w-full bg-gray-100" value="{{ ucfirst($user->getRoleNames()->first()) }}" readonly>
        </div>
    @endrole
</div>
