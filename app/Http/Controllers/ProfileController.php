<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserProfileRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Spatie\Permission\Models\Role;
use App\Traits\HandlesUserProfileUpdate;
use App\Traits\HandlesPhotoProfileUpload;
use App\Traits\HandlesCategoryAssignments;
use App\Traits\HandlesProtectedFields;
use App\Traits\HandlesDocumentUpload;

class ProfileController extends Controller
{
    use HandlesCategoryAssignments, HandlesPhotoProfileUpload, HandlesUserProfileUpdate, HandlesDocumentUpload, HandlesProtectedFields;

    public function edit(User $user)
    {
        $user = auth()->user();
        $this->authorizeEdit($user, $user);

        $profile = $user->hasRole('siswa') ? $user->studentProfile : $user->profile;

        if ($user->hasRole('coach')) {
            $existingHandledCategories = DB::table('coach_student_category')
            ->where('coach_id', $user->id)
            ->get()
            ->map(function ($item) {
                return $item->kategori_umur . '|' . $item->jenis_kelamin;
            })
            ->toArray();
        } else {
            $existingHandledCategories = []; // Default kosong untuk role lain
        }

        $ageGenderOptions = DB::table('student_profiles')
            ->select('kategori_umur', 'jenis_kelamin', DB::raw('COUNT(*) as total_aktif'))
            ->where('status_siswa', 'aktif')
            ->groupBy('kategori_umur', 'jenis_kelamin')
            ->havingraw('COUNT(*) > 0')
            ->orderBy('kategori_umur')
            ->get();

        $isReadOnly = auth()->user()->hasRole('coach'); // true jika coach

        return view('profile.edit', [
            'user' => $user,
            'profile' => $profile,
            'roles' => Role::all(),
            'ageGenderOptions' => $ageGenderOptions,
            'existingHandledCategories' => $existingHandledCategories,
            'isReadOnly' => $isReadOnly,
        ]);
    }

    public function update(UpdateUserProfileRequest $request)
    {   
        $user = auth()->user();

        $this->authorizeEdit($user, $user);

        $data = $request->validated();

        $data = $this->protectStudentFields($request->validated(), $user);

        // Update password jika diisi
        if (!empty($data['password'])) {
            $user->update([
                'password' => Hash::make($data['password']),
            ]);
        }

        // Upload foto profil jika ada
        $path = $this->handlePhotoProfileUpload($request, $user);
        if ($path) {
            $data['photo_profile'] = $path;
        }

        // Update profile (siswa / non-siswa)
        $this->updateUserProfile($user, $data);

        $this->handleStudentDocumentsUpload($request, $user);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }

    public function deleteDocument(string $jenis)
    {
        $user = auth()->user();

        if (!$user->hasRole('siswa')) {
            abort(403);
        }

        $doc = $user->studentProfile->documents()
            ->where('jenis_dokumen', strtolower($jenis))
            ->first();

        if ($doc) {
            Storage::disk('public')->delete($doc->file_path);
            $doc->delete();
        }

        return redirect()->back()->with('success', 'Dokumen berhasil dihapus.');
    }

    protected function updateBasicInfo(User $user, array $data): void
    {
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'] ?? $user->email,
        ]);
    }

    protected function updatePassword(User $user, array $data, User $authUser): void
    {
        if (!empty($data['password'])) {
            if (!$this->isAdminOrManajemen($authUser)) {
                if (!Hash::check($data['current_password'], $user->password)) {
                    back()->withErrors(['current_password' => 'Password lama tidak sesuai'])->throwResponse();
                }
            }

            $user->update(['password' => Hash::make($data['password'])]);
        }
    }

    protected function updateRole(User $user, array $data, User $authUser): void
    {
        if ($this->isAdminOrManajemen($authUser) && !empty($data['role'])) {
            $user->syncRoles([$data['role']]);
        }
    }

    protected function updateProfile(User $user, array $data): void
    {
        $profileData = $this->getProfileData($user, $data);
        
        $relation = $user->hasRole('siswa') ? 'studentProfile' : 'profile';

        $user->{$relation}()->updateOrCreate(['user_id' => $user->id], $profileData);
    }

    protected function getProfileData(User $user, array $data): array
    {
        if ($user->hasRole('siswa')) {
            return collect($data)->only([
                'nama_panggilan', 'jenis_kelamin', 'alamat',
                'tempat_lahir', 'tanggal_lahir',
                'tinggi_badan', 'berat_badan', 'asal_sekolah', 'nomor_whatsapp',
            ])->toArray();
        }

        $fields = collect($data)->only([
            'nama_panggilan', 'jenis_kelamin', 'alamat',
            'tempat_lahir', 'tanggal_lahir'
        ])->toArray();

        if (isset($data['lisensi'])) {
            $fields['lisensi'] = $data['lisensi'];
        }

        if ($this->isAdminOrManajemen(auth()->user()) && isset($data['jabatan'])) {
            $fields['jabatan'] = $data['jabatan'];
        }

        return $fields;
    }

    protected function isAdminOrManajemen(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manajemen']);
    }

    protected function authorizeEdit(User $targetUser, User $authUser): void
    {
        // Admin atau manajemen atau coach boleh edit siapa saja
        if ($authUser->hasAnyRole(['admin', 'manajemen', 'coach'])) {
            return;
        }

        // Siswa hanya boleh edit dirinya sendiri
        if ($authUser->hasAnyRole('siswa') && $authUser->id === $targetUser->id) {
            return;
        }

        abort(403);
    }
}
