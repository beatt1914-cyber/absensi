<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\SekolahController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JadwalKuliahController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\DosenController;

Route::get('/',[DashboardController::class,'index'])->middleware('auth');

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function() {
    Route::resource('kelas',KelasController::class);
    Route::resource('mahasiswa',MahasiswaController::class);
    Route::get('jadwal/pdf', [JadwalKuliahController::class, 'downloadPdf'])->name('jadwal.downloadPdf');
    Route::resource('jadwal', JadwalKuliahController::class);
    
    Route::resource('matakuliah', MataKuliahController::class);
    Route::get('dosen/import', [DosenController::class, 'import'])->name('dosen.import');
    Route::post('dosen/import', [DosenController::class, 'processImport'])->name('dosen.processImport');
    Route::resource('dosen', DosenController::class);

    Route::get('absen',[AbsensiController::class,'index']);
    Route::post('absen',[AbsensiController::class,'store']);
    Route::get('get-siswa',[AbsensiController::class,'getSiswa']);
    Route::get('get-jadwal-options',[AbsensiController::class,'getJadwalOptions']);

    Route::get('rekap',[RekapController::class,'index']);
    Route::get('rekap/pdf', [RekapController::class, 'downloadPdf'])->name('rekap.downloadPdf');

    Route::get('pengaturan',[SekolahController::class,'index']);
    Route::post('pengaturan',[SekolahController::class,'store']);

    Route::get('/post',[PostController::class,'index']);
    Route::post('/post',[PostController::class,'store']);
    Route::post('/like/{id}',[PostController::class,'like']);
    Route::post('/comment',[PostController::class,'comment']);

    // User Profile & Settings
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [UserController::class, 'updateProfile']);
    Route::put('/profile/change-password', [UserController::class, 'updatePassword']);
    Route::delete('/profile/delete-photo', [UserController::class, 'deletePhoto']);
    Route::get('/settings', [UserController::class, 'settings'])->name('settings');
});

// Additional API routes
Route::middleware('auth')->group(function() {
    Route::get('/chart-data', function(){
        return [
            App\Models\Absensi::where('status','H')->count(),
            App\Models\Absensi::where('status','I')->count(),
            App\Models\Absensi::where('status','S')->count(),
            App\Models\Absensi::where('status','A')->count(),
        ];
    });

    Route::get('/mahasiswa-data', function(Request $request){
        return App\Models\Mahasiswa::with('kelas')
        ->where('nama','like','%'.$request->q.'%')
        ->orWhere('nim','like','%'.$request->q.'%')
        ->get();
    });

    Route::delete('/mahasiswa/{id}', function($id){
        App\Models\Mahasiswa::find($id)->delete();
        return response()->json(['success' => true]);
    });
});
