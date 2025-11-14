<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SktpiagammtController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KelurahanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

// Public routes
Route::get('/', function () {
    return view('frontend.home');
});

Route::get('/appv2', function () {
    return view('layout.appv2');
});

Route::get('/loginv3', function () {
    return view('loginv3');
})->name('loginv3');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    
    // Kecamatan dan Kelurahan routes - only Admin and Editor can create/edit/delete
    Route::get('kecamatan', [KecamatanController::class, 'index'])->name('kecamatan.index');
    Route::get('kecamatan/{kecamatan}', [KecamatanController::class, 'show'])->name('kecamatan.show');
    Route::middleware('role:Admin,Editor')->group(function () {
        Route::get('kecamatan/create', [KecamatanController::class, 'create'])->name('kecamatan.create');
        Route::post('kecamatan', [KecamatanController::class, 'store'])->name('kecamatan.store');
        Route::get('kecamatan/{kecamatan}/edit', [KecamatanController::class, 'edit'])->name('kecamatan.edit');
        Route::put('kecamatan/{kecamatan}', [KecamatanController::class, 'update'])->name('kecamatan.update');
        Route::delete('kecamatan/{kecamatan}', [KecamatanController::class, 'destroy'])->name('kecamatan.destroy');
    });
    
    Route::get('kelurahan', [KelurahanController::class, 'index'])->name('kelurahan.index');
    Route::get('kelurahan/{kelurahan}', [KelurahanController::class, 'show'])->name('kelurahan.show');
    Route::middleware('role:Admin,Editor')->group(function () {
        Route::get('kelurahan/create', [KelurahanController::class, 'create'])->name('kelurahan.create');
        Route::post('kelurahan', [KelurahanController::class, 'store'])->name('kelurahan.store');
        Route::get('kelurahan/{kelurahan}/edit', [KelurahanController::class, 'edit'])->name('kelurahan.edit');
        Route::put('kelurahan/{kelurahan}', [KelurahanController::class, 'update'])->name('kelurahan.update');
        Route::delete('kelurahan/{kelurahan}', [KelurahanController::class, 'destroy'])->name('kelurahan.destroy');
    });
    
    // Role management routes - only Admin
    Route::middleware('role:Admin')->group(function () {
        Route::resource('role', RoleController::class);
        Route::post('role/{id}/assign-users', [RoleController::class, 'assignToUsers'])->name('role.assignToUsers');
        
        // User management routes - only Admin
        Route::resource('user', UserController::class);
    });
    
    // SKT Piagam MT routes
    // View & Cetak - semua role bisa (Admin, Editor, Operator, Viewer)
    Route::get('/skt_piagam_mt', [SktpiagammtController::class, 'index'])->name('skt_piagam_mt.index');
    Route::get('/skt_piagam_mt/{skt_piagam_mt}', [SktpiagammtController::class, 'show'])->name('skt_piagam_mt.show');
    Route::get('/skt_piagam_mt/{id}/cetak-skt', [SktpiagammtController::class, 'cetakSkt'])->name('skt_piagam_mt.cetak_skt');
    Route::get('/skt_piagam_mt/{id}/cetak-piagam', [SktpiagammtController::class, 'cetakPiagam'])->name('skt_piagam_mt.cetak_piagam');
    Route::get('/skt-piagam-mt/rekap', [SktpiagammtController::class, 'rekap'])->name('skt_piagam_mt.rekap');
    Route::get('/skt-piagam-mt/trash', [SktpiagammtController::class, 'trash'])->name('skt_piagam_mt.trash');
    Route::get('/skt_piagam_mt/data', [SktpiagammtController::class, 'getData'])->name('skt_piagam_mt.data');
    Route::get('/get-kelurahan', [App\Http\Controllers\SktpiagammtController::class, 'getKelurahan'])->name('get.kelurahan');
    Route::get('/get-next-nomor-statistik', [App\Http\Controllers\SktpiagammtController::class, 'getNextNomorStatistik'])->name('get.next.nomor.statistik');

    // Create, Edit, Upload, Import, Export - hanya Admin, Editor, Operator
    Route::middleware('role:Admin,Editor,Operator')->group(function () {
        Route::get('/skt_piagam_mt/create', [SktpiagammtController::class, 'create'])->name('skt_piagam_mt.create');
        Route::post('/skt_piagam_mt', [SktpiagammtController::class, 'store'])->name('skt_piagam_mt.store');
        Route::get('/skt_piagam_mt/{skt_piagam_mt}/edit', [SktpiagammtController::class, 'edit'])->name('skt_piagam_mt.edit');
        Route::put('/skt_piagam_mt/{skt_piagam_mt}', [SktpiagammtController::class, 'update'])->name('skt_piagam_mt.update');
        
        // Upload files
        Route::post('/skt_piagam_mt/upload-skt', [SktpiagammtController::class, 'uploadSkt'])->name('skt_piagam_mt.upload_skt');
        Route::post('/skt_piagam_mt/upload-piagam', [SktpiagammtController::class, 'uploadPiagam'])->name('skt_piagam_mt.upload_piagam');
        Route::post('/skt_piagam_mt/upload-berkas', [SktpiagammtController::class, 'uploadBerkas'])->name('skt_piagam_mt.upload_berkas');
        
        // Delete files
        Route::delete('/skt_piagam_mt/delete-skt/{id}', [SktpiagammtController::class, 'deleteSkt'])->name('skt_piagam_mt.delete_skt');
        Route::delete('/skt_piagam_mt/delete-piagam/{id}', [SktpiagammtController::class, 'deletePiagam'])->name('skt_piagam_mt.delete_piagam');
        Route::delete('/skt_piagam_mt/delete-berkas/{id}', [SktpiagammtController::class, 'deleteBerkas'])->name('skt_piagam_mt.delete_berkas');
        
        // Import dan export
        Route::post('/skt-piagam-mt/import', [SktpiagammtController::class, 'import'])->name('skt_piagam_mt.import');
        Route::get('/skt-piagam-mt/template', [SktpiagammtController::class, 'downloadTemplate'])->name('skt_piagam_mt.template');
        Route::get('/skt-piagam-mt/export', [SktpiagammtController::class, 'export'])->name('skt_piagam_mt.export');
        
        // Restore
        Route::post('/skt-piagam-mt/{id}/restore', [SktpiagammtController::class, 'restore'])->name('skt_piagam_mt.restore');
    });

    // Delete - hanya Admin & Editor
    Route::middleware('role:Admin,Editor')->group(function () {
        Route::delete('/skt_piagam_mt/{skt_piagam_mt}', [SktpiagammtController::class, 'destroy'])->name('skt_piagam_mt.destroy');
        Route::delete('/skt-piagam-mt/{id}/force-delete', [SktpiagammtController::class, 'forceDelete'])->name('skt_piagam_mt.force_delete');
    });

    // API routes
    Route::get('/api/kelurahans/{kecamatan_id}', function($kecamatan_id) {
        return App\Models\Kelurahan::where('kecamatan_id', $kecamatan_id)->get();
    });
});
