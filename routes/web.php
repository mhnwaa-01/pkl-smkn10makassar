<?php

use App\Http\Controllers\Admin\IndustryController;
use App\Http\Controllers\Admin\MajorController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\MonitoringController;
use Illuminate\Support\Facades\Route;

// Redirect root to login/dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::match(['get', 'post'], '/switch-role/{role}', [AuthController::class, 'switchRole'])->name('switch-role');

// Download Mobile APK for Siswa
Route::get('/download-apk', function () {
    $apkPath = public_path('downloads/PKL-SMKN10-Siswa.apk');
    if (file_exists($apkPath)) {
        return response()->download($apkPath, 'PKL-SMKN10-Siswa.apk', [
            'Content-Type' => 'application/vnd.android.package-archive',
        ]);
    }
    return redirect()->back()->with('error', 'Berkas APK belum tersedia.');
})->name('download.apk');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Presensi Harian
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/export/excel', [AttendanceController::class, 'exportExcel'])->name('attendance.export.excel')->middleware('role:admin,guru,industri');
    Route::get('/attendance/export/pdf', [AttendanceController::class, 'exportPdf'])->name('attendance.export.pdf')->middleware('role:admin,guru,industri');
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.checkIn')->middleware('role:siswa');
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.checkOut')->middleware('role:siswa');

    // Jurnal Harian
    Route::get('/journals', [JournalController::class, 'index'])->name('journals.index');
    Route::get('/journals/export/excel', [JournalController::class, 'exportExcel'])->name('journals.export.excel')->middleware('role:admin,guru,industri');
    Route::get('/journals/export/pdf', [JournalController::class, 'exportPdf'])->name('journals.export.pdf')->middleware('role:admin,guru,industri');
    Route::get('/journals/create', [JournalController::class, 'create'])->name('journals.create')->middleware('role:siswa');
    Route::post('/journals', [JournalController::class, 'store'])->name('journals.store')->middleware('role:siswa');
    Route::post('/journals/{journal}/verify', [JournalController::class, 'verify'])->name('journals.verify')->middleware('role:industri,admin');

    // Monitoring PKL
    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
    Route::get('/monitoring/export/excel', [MonitoringController::class, 'exportExcel'])->name('monitoring.export.excel')->middleware('role:admin,guru');
    Route::get('/monitoring/export/pdf', [MonitoringController::class, 'exportPdf'])->name('monitoring.export.pdf')->middleware('role:admin,guru');
    Route::get('/monitoring/create', [MonitoringController::class, 'create'])->name('monitoring.create')->middleware('role:guru,admin');
    Route::post('/monitoring', [MonitoringController::class, 'store'])->name('monitoring.store')->middleware('role:guru,admin');

    // Penilaian PKL
    Route::get('/evaluations', [EvaluationController::class, 'index'])->name('evaluations.index');
    Route::get('/evaluations/export/excel', [EvaluationController::class, 'exportExcel'])->name('evaluations.export.excel')->middleware('role:admin,guru,industri');
    Route::get('/evaluations/export/pdf', [EvaluationController::class, 'exportPdf'])->name('evaluations.export.pdf')->middleware('role:admin,guru,industri');
    Route::post('/evaluations/{student}', [EvaluationController::class, 'storeOrUpdate'])->name('evaluations.storeOrUpdate')->middleware('role:industri,guru,admin');
    Route::delete('/evaluations/{student}', [EvaluationController::class, 'destroy'])->name('evaluations.destroy')->middleware('role:industri,guru,admin');

    // Admin Only Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('students', StudentController::class)->except(['create', 'show', 'edit']);
        Route::resource('majors', MajorController::class)->except(['create', 'show', 'edit']);
        Route::resource('industries', IndustryController::class)->except(['create', 'show', 'edit']);
        Route::resource('teachers', TeacherController::class)->except(['create', 'show', 'edit']);
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
    });

});
