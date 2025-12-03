<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SktpiagammtController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KelurahanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::view('/', 'frontend.home');
Route::view('/appv2', 'layout.appv2');
Route::view('/loginv3', 'loginv3')->name('loginv3');
Route::view('/login', 'login')->name('login');

Route::post('/login', [LoginController::class, 'authenticate'])
    ->name('login.authenticate');


/*
|--------------------------------------------------------------------------
| Protected Routes (Auth)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Kecamatan
    |--------------------------------------------------------------------------
    */

    Route::get('kecamatan', [KecamatanController::class, 'index'])->name('kecamatan.index');
    
    Route::middleware(['role:Admin,Editor'])->group(function () {
        Route::get('kecamatan/create', [KecamatanController::class, 'create'])->name('kecamatan.create');
        Route::post('kecamatan', [KecamatanController::class, 'store'])->name('kecamatan.store');
        Route::get('kecamatan/{kecamatan}/edit', [KecamatanController::class, 'edit'])->name('kecamatan.edit');
        Route::put('kecamatan/{kecamatan}', [KecamatanController::class, 'update'])->name('kecamatan.update');
        Route::delete('kecamatan/{kecamatan}', [KecamatanController::class, 'destroy'])->name('kecamatan.destroy');
    });
    
    Route::get('kecamatan/{kecamatan}', [KecamatanController::class, 'show'])
        ->whereNumber('kecamatan')
        ->name('kecamatan.show');

    /*
    |--------------------------------------------------------------------------
    | Kelurahan
    |--------------------------------------------------------------------------
    */

    Route::get('kelurahan', [KelurahanController::class, 'index'])->name('kelurahan.index');


    Route::middleware(['role:Admin,Editor'])->group(function () {
        Route::get('kelurahan/create', [KelurahanController::class, 'create'])->name('kelurahan.create');
        Route::post('kelurahan', [KelurahanController::class, 'store'])->name('kelurahan.store');
        Route::get('kelurahan/{kelurahan}/edit', [KelurahanController::class, 'edit'])->name('kelurahan.edit');
        Route::put('kelurahan/{kelurahan}', [KelurahanController::class, 'update'])->name('kelurahan.update');
        Route::delete('kelurahan/{kelurahan}', [KelurahanController::class, 'destroy'])->name('kelurahan.destroy');
    });

    Route::get('kelurahan/{kelurahan}', [KelurahanController::class, 'show'])
        ->whereNumber('kelurahan')
        ->name('kelurahan.show');

    /*
    |--------------------------------------------------------------------------
    | Role & User Management (Admin Only)
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:Admin'])->group(function () {
        Route::resource('role', RoleController::class);
        Route::post('role/{id}/assign-users', [RoleController::class, 'assignToUsers'])
            ->name('role.assignToUsers');

        Route::resource('user', UserController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | SKT Piagam MT
    |--------------------------------------------------------------------------
    | Catatan: Rute kompleks — urutan penting!
    | Semua rute khusus (cetak, import, export, trash, rekap) ditempatkan
    | sebelum rute dinamis {skt_piagam_mt}.
    |--------------------------------------------------------------------------
    */

    // bebas role, hanya butuh login
    Route::get('/skt-piagam-mt', [SktpiagammtController::class, 'index'])->name('skt_piagam_mt.index');
    Route::get('/skt-piagam-mt/data', [SktpiagammtController::class, 'getData'])->name('skt_piagam_mt.data');
    Route::get('/skt-piagam-mt/rekap', [SktpiagammtController::class, 'rekap'])->name('skt_piagam_mt.rekap');
    Route::get('/skt-piagam-mt/trash', [SktpiagammtController::class, 'trash'])->name('skt_piagam_mt.trash');

    Route::get('/get-kelurahan', [SktpiagammtController::class, 'getKelurahan'])->name('get.kelurahan');
    Route::get('/get-next-nomor-statistik', [SktpiagammtController::class, 'getNextNomorStatistik'])->name('get.next.nomor.statistik');

    // khusus Admin, Editor, Operator
    Route::middleware(['role:Admin,Editor,Operator'])->group(function () {

        Route::get('/skt-piagam-mt/create', [SktpiagammtController::class, 'create'])->name('skt_piagam_mt.create');
        Route::post('/skt-piagam-mt', [SktpiagammtController::class, 'store'])->name('skt_piagam_mt.store');

        Route::get('/skt-piagam-mt/{skt_piagam_mt}/edit', [SktpiagammtController::class, 'edit'])->name('skt_piagam_mt.edit');
        Route::put('/skt-piagam-mt/{skt_piagam_mt}', [SktpiagammtController::class, 'update'])->name('skt_piagam_mt.update');

        // uploads
        Route::post('/skt-piagam-mt/upload-skt', [SktpiagammtController::class, 'uploadSkt'])->name('skt_piagam_mt.upload_skt');
        Route::post('/skt-piagam-mt/upload-piagam', [SktpiagammtController::class, 'uploadPiagam'])->name('skt_piagam_mt.upload_piagam');
        Route::post('/skt-piagam-mt/upload-berkas', [SktpiagammtController::class, 'uploadBerkas'])->name('skt_piagam_mt.upload_berkas');

        // delete file
        Route::delete('/skt-piagam-mt/delete-skt/{id}', [SktpiagammtController::class, 'deleteSkt'])->name('skt_piagam_mt.delete_skt');
        Route::delete('/skt-piagam-mt/delete-piagam/{id}', [SktpiagammtController::class, 'deletePiagam'])->name('skt_piagam_mt.delete_piagam');
        Route::delete('/skt-piagam-mt/delete-berkas/{id}', [SktpiagammtController::class, 'deleteBerkas'])->name('skt_piagam_mt.delete_berkas');

        // import/export
        Route::post('/skt-piagam-mt/import', [SktpiagammtController::class, 'import'])->name('skt_piagam_mt.import');
        Route::get('/skt-piagam-mt/template', [SktpiagammtController::class, 'downloadTemplate'])->name('skt_piagam_mt.template');
        Route::get('/skt-piagam-mt/export', [SktpiagammtController::class, 'export'])->name('skt_piagam_mt.export');

        // restore
        Route::post('/skt-piagam-mt/{id}/restore', [SktpiagammtController::class, 'restore'])->name('skt_piagam_mt.restore');
    });

    // Admin & Editor only
    Route::middleware(['role:Admin,Editor'])->group(function () {
        Route::delete('/skt-piagam-mt/{skt_piagam_mt}', [SktpiagammtController::class, 'destroy'])->name('skt_piagam_mt.destroy');
        Route::delete('/skt-piagam-mt/{id}/force-delete', [SktpiagammtController::class, 'forceDelete'])->name('skt_piagam_mt.force_delete');
    });

    // Route Cetak - urutan sebelum route {id}
    Route::get('/skt-piagam-mt/{id}/cetak-skt', [SktpiagammtController::class, 'cetakSkt'])->name('skt_piagam_mt.cetak_skt');
    Route::get('/skt-piagam-mt/{id}/cetak-piagam', [SktpiagammtController::class, 'cetakPiagam'])->name('skt_piagam_mt.cetak_piagam');
    Route::get('/skt-piagam-mt/{id}/preview-piagam', [SktpiagammtController::class, 'previewPiagam'])->name('skt_piagam_mt.preview_piagam');

    // Route paling generik → taruh paling bawah
    Route::get('/skt-piagam-mt/{skt_piagam_mt}', [SktpiagammtController::class, 'show'])->name('skt_piagam_mt.show');


    /*
    |--------------------------------------------------------------------------
    | API (Tetap di dalam auth)
    |--------------------------------------------------------------------------
    */

    Route::get('/api/kelurahans/{kecamatan_id}', function ($kecamatan_id) {
        return App\Models\Kelurahan::where('kecamatan_id', $kecamatan_id)->get();
    });
});
