<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SktpiagammtController;

// Public routes
Route::get('/', function () {
    return view('frontend.home');
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
    
    // SKT Piagam MT routes
    // Route::get('/skt_piagam_mt', [SktPiagamMtController::class, 'index'])->name('skt_piagam_mt.index');
    Route::get('/skt_piagam_mt/data', [SktPiagamMtController::class, 'getData'])->name('skt_piagam_mt.data');
    Route::resource('skt_piagam_mt', SktpiagammtController::class);

    // Cetak dokumen
    Route::get('/skt_piagam_mt/{id}/cetak-skt', [SktpiagammtController::class, 'cetakSkt'])
        ->name('skt_piagam_mt.cetak_skt');
    Route::get('/skt_piagam_mt/{id}/cetak-piagam', [SktpiagammtController::class, 'cetakPiagam'])
        ->name('skt_piagam_mt.cetak_piagam');

    // API routes
    Route::get('/api/kelurahans/{kecamatan_id}', function($kecamatan_id) {
        return App\Models\Kelurahan::where('kecamatan_id', $kecamatan_id)->get();
    });

    Route::get('/get-kelurahan', [App\Http\Controllers\SktpiagammtController::class, 'getKelurahan'])
        ->name('get.kelurahan');
    Route::get('/get-next-nomor-statistik', [App\Http\Controllers\SktpiagammtController::class, 'getNextNomorStatistik'])
        ->name('get.next.nomor.statistik');

    // Import dan export
    Route::post('/skt-piagam-mt/import', [SktpiagammtController::class, 'import'])
        ->name('skt_piagam_mt.import');
    Route::get('/skt-piagam-mt/template', [SktpiagammtController::class, 'downloadTemplate'])
        ->name('skt_piagam_mt.template');
    Route::get('/skt-piagam-mt/export', [SktpiagammtController::class, 'export'])
        ->name('skt_piagam_mt.export');

    // Upload dan delete files
    Route::post('/skt_piagam_mt/upload-skt', [SktpiagammtController::class, 'uploadSkt'])
        ->name('skt_piagam_mt.upload_skt');
    Route::post('/skt_piagam_mt/upload-piagam', [SktpiagammtController::class, 'uploadPiagam'])
        ->name('skt_piagam_mt.upload_piagam');
    Route::post('/skt_piagam_mt/upload-berkas', [SktpiagammtController::class, 'uploadBerkas'])
        ->name('skt_piagam_mt.upload_berkas');
    Route::delete('/skt_piagam_mt/delete-skt/{id}', [SktpiagammtController::class, 'deleteSkt'])
        ->name('skt_piagam_mt.delete_skt');
    Route::delete('/skt_piagam_mt/delete-piagam/{id}', [SktpiagammtController::class, 'deletePiagam'])
        ->name('skt_piagam_mt.delete_piagam');
    Route::delete('/skt_piagam_mt/delete-berkas/{id}', [SktpiagammtController::class, 'deleteBerkas'])
        ->name('skt_piagam_mt.delete_berkas');

    // Rekap dan trash management
    Route::get('/skt-piagam-mt/rekap', [SktpiagammtController::class, 'rekap'])
        ->name('skt_piagam_mt.rekap');
    Route::get('/skt-piagam-mt/trash', [SktpiagammtController::class, 'trash'])
        ->name('skt_piagam_mt.trash');
    Route::post('/skt-piagam-mt/{id}/restore', [SktpiagammtController::class, 'restore'])
        ->name('skt_piagam_mt.restore');
    Route::delete('/skt-piagam-mt/{id}/force-delete', [SktpiagammtController::class, 'forceDelete'])
        ->name('skt_piagam_mt.force_delete');
});
