{{-- resources/views/partials/profile/base-fields.blade.php --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    {{-- Name --}}
    <div>
        <label class="label" for="name">
            <span class="label-text">Nama Lengkap</span>
        </label>
        <input type="text" name="name" id="name"
            class="input input-bordered w-full"
            value="{{ old('name', $user->name) }}" />
    </div>

    {{-- Email --}}
    <div>
        <label class="label" for="email">
            <span class="label-text">Email</span>
        </label>
        <input type="email" name="email" id="email"
            class="input input-bordered w-full"
            value="{{ old('email', $user->email) }}" />
    </div>

    {{-- Role (hanya jika mode admin dan bisa diubah) --}}
    @if(auth()->user()->hasRole('admin'))
    <div class="md:col-span-2">
        <label class="label" for="role">
            <span class="label-text">Role</span>
        </label>
        <select name="role" id="role" class="select select-bordered w-full">
            @foreach($roles as $role)
                <option value="{{ $role->name }}" @selected($user->hasRole($role->name))>
                    {{ ucfirst($role->name) }}
                </option>
            @endforeach
        </select>
    </div>
    @endif

    {{-- Current Password (hanya jika edit profil sendiri) --}}
    @if(auth()->id() === $user->id)
    <div class="md:col-span-2">
        <label class="label" for="current_password">
            <span class="label-text">Password Lama</span>
        </label>
        <input type="password" name="current_password" id="current_password"
            class="input input-bordered w-full"/>
    </div>
    @endif

    {{-- Password --}}
    <div>
        <label class="label" for="password">
            <span class="label-text">Password Baru</span>
        </label>
        <input type="password" name="password" id="password"
            class="input input-bordered w-full" />
    </div>

    {{-- Confirm Password --}}
    <div>
        <label class="label" for="password_confirmation">
            <span class="label-text">Konfirmasi Password</span>
        </label>
        <input type="password" name="password_confirmation" id="password_confirmation"
            class="input input-bordered w-full" />
    </div>
</div>
