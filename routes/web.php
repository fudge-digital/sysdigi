<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\Coach\AttendanceController;
use App\Http\Controllers\Admin\StudentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Routes untuk halaman publik dan autentikasi dasar
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('auth.login'));
Route::post('/logout', [DashboardController::class, 'logout'])->name('logout');

require __DIR__.'/auth.php';


/*
|--------------------------------------------------------------------------
| Routes untuk pengguna yang telah login
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Dashboard berdasarkan role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/admin', [DashboardController::class, 'adminDashboard'])->middleware('role:admin')->name('admin.dashboard');
    Route::get('/dashboard/coach', [DashboardController::class, 'coachDashboard'])->middleware('role:coach')->name('coach.dashboard');
    Route::get('/dashboard/siswa', [DashboardController::class, 'siswaDashboard'])->middleware('role:siswa')->name('siswa.dashboard');
    Route::get('/dashboard/admin/siswa/{id}', [StudentController::class, 'show'])->middleware(['auth', 'role:admin'])->name('siswa.show');

    // Edit profil pribadi
    Route::get('/profil/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
});

Route::delete('/profil/document/{jenis}', [ProfileController::class, 'deleteDocument'])
    ->name('profile.document.delete')
    ->middleware(['auth']);

Route::delete('/admin/users/{user}/document/{jenis}', [UserManagementController::class, 'deleteDocument'])
    ->name('admin.delete_document')
    ->middleware(['auth']);
/*
|--------------------------------------------------------------------------
| Routes untuk user management (admin, manajemen, coach)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin|manajemen|coach'])
    ->prefix('users')
    ->name('users.')
    ->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::get('/create', [UserManagementController::class, 'create'])->name('create');
        Route::post('/store', [UserManagementController::class, 'store'])->name('store');

        Route::get('/export-siswa', [ExportController::class, 'exportSiswa'])->name('export.siswa');

        Route::get('/{user}', [UserManagementController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
    });

/*
|--------------------------------------------------------------------------
| Route khusus import siswa (hanya admin)
|--------------------------------------------------------------------------
*/

Route::post('/users/import-siswa', [UserManagementController::class, 'importSiswa'])
    ->name('users.import.siswa')
    ->middleware(['auth', 'role:admin']);


/*
|--------------------------------------------------------------------------
| Route khusus untuk role coach
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:coach'])->prefix('coach')->name('coach.')->group(function () {
    Route::get('/absensi/create', [AttendanceController::class, 'create'])->name('absensi.create');
    Route::post('/absensi', [AttendanceController::class, 'store'])->name('absensi.store');
    Route::get('/absensi', [AttendanceController::class, 'index'])->name('absensi.index');
    Route::get('absensi/{attendance}', [AttendanceController::class, 'show'])->name('absensi.show');
});

/*
|--------------------------------------------------------------------------
| Route khusus untuk role siswa
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/absensi', [\App\Http\Controllers\Siswa\AttendanceController::class, 'index'])->name('siswa.absensi.index');
});