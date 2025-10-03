<?php

use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\{
    PengumpulanController,
    MateriController,
    KelasController,
    TugasController,
    DashboardController,
    NotificationController
};

Route::get('/', function () {
    return view('welcome');
});

// ==============================
// Semua user login
// ==============================
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ---------- KELAS ----------
    Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');

    Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', 'Admin\UserController@index')->name('users.index');
        Route::get('/users/{user}/edit', 'Admin\UserController@edit')->name('users.edit');
        Route::put('/users/{user}', 'Admin\UserController@update')->name('users.update');
        Route::delete('/users/{user}', 'Admin\UserController@destroy')->name('users.destroy');

        Route::post('/users/{user}/set-role', 'Admin\UserController@setRole')->name('users.setRole');
        Route::post('/users/{user}/toggle-active', 'Admin\UserController@toggleActive')->name('users.toggleActive');

        // import/export
        Route::post('/users/import', 'Admin\UserController@import')->name('users.import');
        Route::get('/users/export', 'Admin\UserController@export')->name('users.export');
    });


    // ==============================
    // Guru & Admin
    // ==============================
    Route::middleware([CheckRole::class . ':Guru'])->group(function () {
        // Kelas
        Route::get('/kelas/create', [KelasController::class, 'create'])->name('kelas.create');
        Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
        Route::get('/kelas/{kelas}/edit', [KelasController::class, 'edit'])->name('kelas.edit');
        Route::put('/kelas/{kelas}', [KelasController::class, 'update'])->name('kelas.update');
        Route::delete('/kelas/{kelas}', [KelasController::class, 'destroy'])->name('kelas.destroy');

        // Materi
        Route::get('/kelas/{kelas}/materi/create', [MateriController::class, 'create'])->name('kelas.materi.create');
        Route::post('/kelas/{kelas}/materi', [MateriController::class, 'store'])->name('kelas.materi.store');
        Route::delete('/materi/{materi}', [MateriController::class, 'destroy'])->name('materi.destroy');
        
        // Tugas
        Route::get('/kelas/{kelas}/tugas/create', [TugasController::class, 'create'])->name('kelas.tugas.create');
        Route::post('/kelas/{kelas}/tugas', [TugasController::class, 'store'])->name('kelas.tugas.store');
        Route::get('/tugas/{tugas}/edit', [TugasController::class, 'edit'])->name('tugas.edit');
        Route::put('/tugas/{tugas}', [TugasController::class, 'update'])->name('tugas.update');
        Route::delete('/tugas/{tugas}', [TugasController::class, 'destroy'])->name('tugas.destroy');

        // ---------- PENGUMPULAN (GURU: lihat per tugas) ----------
        Route::get('/tugas/{tugas}/pengumpulan', [PengumpulanController::class, 'listByTugas'])
            ->name('tugas.pengumpulan.index');

        // Pengumpulan (nilai)
        Route::get('/pengumpulan/{pengumpulan}/nilai', [PengumpulanController::class, 'editNilai'])->name('pengumpulan.editNilai');
        Route::patch('/pengumpulan/{pengumpulan}/nilai', [PengumpulanController::class, 'updateNilai'])->name('pengumpulan.updateNilai');
    
    
    });

    // ==============================
    // Siswa
    // ==============================
    Route::middleware([CheckRole::class . ':Siswa'])->group(function () {
        // Join kelas
        Route::post('/kelas/join', [KelasController::class, 'join'])->name('kelas.join');

        Route::get('/materi/{materi}', [MateriController::class, 'show'])->name('materi.show'); // akses tambahan di controller
        Route::get('/tugas/show/{tugas}', [TugasController::class, 'show'])->name('tugas.show');


        // Pengumpulan (CRUD siswa)
        Route::get('/pengumpulan', [PengumpulanController::class, 'index'])->name('pengumpulan.index');
        Route::get('/pengumpulan/create', [PengumpulanController::class, 'create'])->name('pengumpulan.create');
        Route::post('/pengumpulan', [PengumpulanController::class, 'store'])->name('pengumpulan.store');
        Route::delete('/pengumpulan/{pengumpulan}', [PengumpulanController::class, 'destroy'])->name('pengumpulan.destroy');
    });

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index'); // halaman lengkap
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');

    // endpoint JSON untuk polling
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
    Route::get('/notifications/latest', [NotificationController::class, 'latest'])->name('notifications.latest'); // return array notifs

    Route::get('/pengumpulan/{pengumpulan}', [PengumpulanController::class, 'show'])->name('pengumpulan.show');
    Route::get('/materi/{materi}', [MateriController::class, 'show'])->name('materi.show'); // akses tambahan di controller
    Route::get('/tugas/show/{tugas}', [TugasController::class, 'show'])->name('tugas.show');

    Route::get('/kelas/{kelas}', [KelasController::class, 'show'])->name('kelas.show');
});

// ==============================
// Profile (untuk semua user login)
// ==============================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
