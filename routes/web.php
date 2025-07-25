<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\KrsController;
use App\Http\Controllers\ScheduleController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Route Dosen
Route::middleware(['auth', 'is_dosen'])->group(function () {
    // Resource route untuk CourseController
    Route::resource('courses', CourseController::class);

    // Page Mata Kuliah
    Route::get('/dosen/mata-kuliah', function () {
        return view('dosen.mata-kuliah');
    })->name('dosen.mata_kuliah');

    // Page Input Nilai
    Route::get('/dosen/input-nilai', [GradeController::class, 'inputGradeForm'])->name('dosen.input_nilai');

    Route::post('/dosen/input-nilai', [GradeController::class, 'storeGrade'])->name('dosen.store_nilai');

    // Page untuk Validasi KRS Dosen
    Route::get('/dosen/krs-validation', [KrsController::class, 'showValidationList'])->name('dosen.krs_validation');

    Route::post('/dosen/krs-validation/{enrollment}', [KrsController::class, 'validateKrs'])->name('dosen.validate_krs');

    // Manajemen Jadwal Kuliah
    Route::get('/dosen/schedules', [ScheduleController::class, 'index'])->name('dosen.schedules_index');

    Route::get('/dosen/schedules/create', [ScheduleController::class, 'create'])->name('dosen.schedules_create');

    Route::post('/dosen/schedules', [ScheduleController::class, 'store'])->name('dosen.schedules_store');

    Route::get('/dosen/schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('dosen.schedules_edit');

    Route::put('/dosen/schedules/{schedule}', [ScheduleController::class, 'update'])->name('dosen.schedules_update');

    Route::delete('/dosen/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('dosen.schedules_destroy');
});

// Route Mahasiswa
Route::middleware(['auth', 'is_mahasiswa'])->group(function () {
    // Contoh halaman untuk Mahasiswa
    Route::get('/mahasiswa/jadwal', [ScheduleController::class, 'showStudentSchedule'])->name('mahasiswa.jadwal');

    // Page Transkrip Nilai
    Route::get('/mahasiswa/transkrip', [GradeController::class, 'showTranskrip'])->name('mahasiswa.transkrip');

    // Page Kartu Rencana Studi (KRS)
    Route::get('/mahasiswa/krs', [KrsController::class, 'showKrsForm'])->name('mahasiswa.krs_form');

    Route::post('/mahasiswa/krs', [KrsController::class, 'storeKrs'])->name('mahasiswa.store_krs');

    Route::delete('/mahasiswa/krs/{enrollment}', [KrsController::class, 'deleteKrs'])->name('mahasiswa.delete_krs');
});
