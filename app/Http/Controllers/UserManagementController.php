<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\StudentProfile;
use App\Imports\SiswaImport;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Intervention\Image\Facades\Image;
use App\Traits\HandlesUserProfileUpdate;
use App\Traits\HandlesPhotoProfileUpload;
use App\Traits\HandlesCategoryAssignments;

class UserManagementController extends Controller
{
    use HandlesCategoryAssignments, HandlesPhotoProfileUpload, HandlesUserProfileUpdate;
    //
    public function index(Request $request)
    {
        $query = User::with(['roles', 'studentProfile']);

    // Filter berdasarkan role
    if ($request->filled('role')) {
        $query->role($request->role);
    }

     // Filter berdasarkan kategori_umur dan jenis_kelamin gabungan
    if ($request->filled('kategori_umur_jenis_kelamin')) {
        [$kategori, $jk] = explode('|', $request->kategori_umur_jenis_kelamin);
        $query->whereHas('studentProfile', fn ($q) =>
            $q->where('kategori_umur', $kategori)
            ->where('jenis_kelamin', $jk)
    );
}

    // Filter berdasarkan status siswa
    if ($request->filled('status_siswa')) {
        $query->whereHas('studentProfile', fn ($q) =>
            $q->where('status_siswa', $request->status_siswa)
        );
    }

    // Search
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%$search%")
            ->orWhere('niss', 'like', "%$search%")
            ->orWhereHas('studentProfile', fn($q2) =>
                $q2->where('nama_panggilan', 'like', "%$search%")
                    ->orWhere('nomor_jersey', 'like', "%$search%")
            );
        });
    }

    $users = $query->orderBy('users.niss')->paginate(15)->appends($request->query());

    // Ambil list role dan hitung status siswa
    $roles = Role::pluck('name');

    $kategoriGenderOptions = DB::table('student_profiles')
        ->select(
            DB::raw("CONCAT(kategori_umur, ' ', jenis_kelamin) AS label"),
            'kategori_umur',
            'jenis_kelamin'
        )
        ->distinct()
        ->orderBy('kategori_umur')
        ->get();

    return view('users.index', compact(
        'users',
        'roles',
        'kategoriGenderOptions',
        'request' // Untuk mempertahankan filter saat paginasi
    ));
        
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function create()
    {

        $user = null;

        $authUser = auth()->user();

        //$roles = Role::pluck('name', 'name');
        $roles = match(true) {
            $authUser->hasRole('admin') => Role::all(),
            $authUser->hasRole('manajemen') => Role::whereIn('name', ['coach', 'siswa'])->get(),
            default => abort(403),
        };

        $ageGenderOptions = DB::table('student_profiles')
            ->select('kategori_umur', 'jenis_kelamin', DB::raw('COUNT(*) as total_aktif'))
            ->where('status_siswa', 'aktif')
            ->groupBy('kategori_umur', 'jenis_kelamin')
            ->havingRaw('COUNT(*) > 0')
            ->orderBy('kategori_umur')
            ->get();

        $existingHandledCategories = []; // default kosong saat create

        // Ambil nilai unik dari student_profiles
        $kelompokUmurList = StudentProfile::select('kategori_umur')->distinct()->pluck('kategori_umur');
        $jenisKelaminList = StudentProfile::select('jenis_kelamin')->distinct()->pluck('jenis_kelamin');

        return view('users.create', compact('user', 'roles', 'kelompokUmurList', 'jenisKelaminList', 'ageGenderOptions', 'existingHandledCategories') + ['isEdit' => false]);
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'niss' => $data['niss'],
            'email' => $data['email'] ?? null,
            'password' => Hash::make($data['password']),
        ]);

        $user->syncRoles([$data['role']]);

        // Upload foto profil jika ada
        $path = $this->handlePhotoProfileUpload($request, $user);
        if ($path) {
            $data['photo_profile'] = $path;
        }

        $this->updateUserProfile($user, $data);

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat!');
    }

    public function edit(User $user)
    {
        $thisUser = auth()->user();
        
        if (!$thisUser->hasAnyRole(['admin', 'manajemen']) && $thisUser->id !== $user->id) {
            abort(403);
        }

        $roles = Role::all();
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

        // Jika user login bukan admin atau manajemen dan bukan dirinya sendiri → tolak
        if (!auth()->user()->hasAnyRole(['admin', 'manajemen']) && auth()->id() !== $user->id) {
            abort(403);
        }

        return view('users.edit', compact('user', 'roles', 'profile', 'ageGenderOptions', 'existingHandledCategories', 'isReadOnly'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        // $this->authorizeEdit(auth()->user(), $user);

        $data = $request->validated();

        $user->update([
            'name' => $data['name'],
            'niss' => $data['niss'],
            'email' => $data['email'] ?? null,
        ]);

        // Update password jika diisi
        if (!empty($data['password'])) {
            $user->update([
                'password' => Hash::make($data['password']),
            ]);
        }

        // Admin bisa ubah role
        if (auth()->user()->hasRole('admin') && isset($data['role'])) {
            $user->syncRoles([$data['role']]);
        }

        // Upload dan simpan photo_profile
        $path = $this->handlePhotoProfileUpload($request, $user);
        if ($path) {
            $data['photo_profile'] = $path;
        }

        // Update profile
        $this->updateUserProfile($user, $data);

        // Jika coach dan admin mengirimkan handled_categories
        if (
            auth()->user()->hasRole('admin') &&
            $user->hasRole('coach') &&
            $request->filled('handled_categories')
        ) {
            $this->syncHandledCategories($user, $request->input('handled_categories', []));
        }

        return redirect()->route('users.index')->with('success', 'User updated!');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted.');
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

    public function importSiswa(Request $request)
    {
        $request->validate([
        'file' => 'required|file|mimes:xlsx,csv,xls',
        ]);

        try {
            Excel::import(new SiswaImport, $request->file('file'));

            return back()->with('success', 'Import siswa berhasil!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

}
