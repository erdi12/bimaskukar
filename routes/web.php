<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SktpiagammtController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [\App\Http\Controllers\FrontendController::class, 'index'])->name('home');
Route::get('/profil', [\App\Http\Controllers\FrontendController::class, 'profil'])->name('profil');
Route::get('/layanan/{slug}', [\App\Http\Controllers\FrontendController::class, 'detailLayanan'])->name('layanan.detail');
Route::get('/kontak', [\App\Http\Controllers\FrontendController::class, 'kontak'])->name('kontak');
Route::get('/data-keagamaan', [\App\Http\Controllers\FrontendController::class, 'dataKeagamaan'])->name('data_keagamaan');

// Cek Validitas - Form pencarian
Route::get('/cek-validitas', [\App\Http\Controllers\ValiditasController::class, 'index'])
    ->name('cek_validitas');

// Cek Validitas - Search (POST untuk keamanan lebih baik)
Route::post('/cek-validitas/search', [\App\Http\Controllers\ValiditasController::class, 'search'])
    ->name('cek_validitas.search')
    ->middleware('throttle:10,1'); // 10 requests per menit untuk mencegah brute force

// Cek Validitas - Detail dengan UUID (lebih aman)
Route::get('/cek-validitas/{type}/{uuid}', [\App\Http\Controllers\ValiditasController::class, 'show'])
    ->name('cek_validitas.show')
    ->middleware('throttle:20,1'); // 20 requests per menit (lebih longgar karena sudah punya UUID)

// Marbot Registration
Route::get('/registrasi-marbot', [\App\Http\Controllers\MarbotFrontendController::class, 'create'])->name('marbot.register');
Route::post('/registrasi-marbot', [\App\Http\Controllers\MarbotFrontendController::class, 'store'])->name('marbot.frontend.store');
Route::get('/registrasi-marbot/{id}/edit', [\App\Http\Controllers\MarbotFrontendController::class, 'edit'])->name('marbot.frontend.edit');
Route::put('/registrasi-marbot/{id}', [\App\Http\Controllers\MarbotFrontendController::class, 'update'])->name('marbot.frontend.update');
Route::get('/check-rumah-ibadah', [\App\Http\Controllers\MarbotFrontendController::class, 'checkRumahIbadah'])->name('check.rumah_ibadah');

// API Public (For Dropdowns)
Route::get('/api/kelurahans/{kecamatan_id}', function ($kecamatan_id) {
    return App\Models\Kelurahan::where('kecamatan_id', $kecamatan_id)->get();
})->name('api.kelurahans');

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

    Route::get('/appv2', [DashboardController::class, 'index'])->name('dashboard_v2');

    /*
    |--------------------------------------------------------------------------
    | ADMIN SYSTEM ACCESS
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:Admin'])->group(function () {
        // User & Role Management
        Route::resource('appv2/users', \App\Http\Controllers\UserV2Controller::class)->names('users_v2');
        Route::resource('appv2/roles', \App\Http\Controllers\RoleV2Controller::class)->names('roles_v2');

        // Audit Log
        Route::get('appv2/audit-log', [\App\Http\Controllers\AuditLogController::class, 'index'])->name('audit_log.index');

        // Marbot System Actions
        Route::post('appv2/marbot/download-archive', [\App\Http\Controllers\MarbotController::class, 'downloadArchive'])->name('marbot.download_archive');
        Route::put('appv2/marbot/settings', [\App\Http\Controllers\MarbotController::class, 'updateSettings'])->name('marbot.settings.update');
    });

    /*
    |--------------------------------------------------------------------------
    | WRITE ACCESS (Admin, Editor, Operator)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:Admin,Editor,Operator'])->group(function () {

        // SKT Piagam MT V2 (Write)
        Route::get('/skt-piagam-mt-v2/create', [\App\Http\Controllers\SktpiagammtV2Controller::class, 'create'])->name('skt_piagam_mt_v2.create');
        Route::post('/skt-piagam-mt-v2', [\App\Http\Controllers\SktpiagammtV2Controller::class, 'store'])->name('skt_piagam_mt_v2.store');
        Route::get('/skt-piagam-mt-v2/{skt_piagam_mt}/edit', [\App\Http\Controllers\SktpiagammtV2Controller::class, 'edit'])->name('skt_piagam_mt_v2.edit');
        Route::put('/skt-piagam-mt-v2/{skt_piagam_mt}', [\App\Http\Controllers\SktpiagammtV2Controller::class, 'update'])->name('skt_piagam_mt_v2.update');
        Route::delete('/skt-piagam-mt-v2/{id}', [\App\Http\Controllers\SktpiagammtV2Controller::class, 'destroy'])->name('skt_piagam_mt_v2.destroy');

        // Uploads & Deletions
        Route::post('/skt-piagam-mt-v2/upload-skt', [\App\Http\Controllers\SktpiagammtV2Controller::class, 'uploadSkt'])->name('skt_piagam_mt_v2.upload_skt');
        Route::post('/skt-piagam-mt-v2/upload-piagam', [\App\Http\Controllers\SktpiagammtV2Controller::class, 'uploadPiagam'])->name('skt_piagam_mt_v2.upload_piagam');
        Route::post('/skt-piagam-mt-v2/upload-berkas', [\App\Http\Controllers\SktpiagammtV2Controller::class, 'uploadBerkas'])->name('skt_piagam_mt_v2.upload_berkas');
        Route::delete('/skt-piagam-mt-v2/delete-skt/{id}', [\App\Http\Controllers\SktpiagammtV2Controller::class, 'deleteSkt'])->name('skt_piagam_mt_v2.delete_skt');
        Route::delete('/skt-piagam-mt-v2/delete-piagam/{id}', [\App\Http\Controllers\SktpiagammtV2Controller::class, 'deletePiagam'])->name('skt_piagam_mt_v2.delete_piagam');
        Route::delete('/skt-piagam-mt-v2/delete-berkas/{id}', [\App\Http\Controllers\SktpiagammtV2Controller::class, 'deleteBerkas'])->name('skt_piagam_mt_v2.delete_berkas');
        Route::post('/skt-piagam-mt-v2/restore/{id}', [\App\Http\Controllers\SktpiagammtV2Controller::class, 'restore'])->name('skt_piagam_mt_v2.restore');
        Route::delete('/skt-piagam-mt-v2/force-delete/{id}', [\App\Http\Controllers\SktpiagammtV2Controller::class, 'forceDelete'])->name('skt_piagam_mt_v2.force_delete');

        // Resources Write (Excluding Index/Show which are in Read-Only group)
        Route::resource('appv2/pegawai', \App\Http\Controllers\PegawaiController::class)->names('pegawai')->except(['index', 'show']);
        Route::resource('appv2/kecamatan', \App\Http\Controllers\KecamatanV2Controller::class)->names('kecamatan_v2')->except(['index', 'show']);
        Route::resource('appv2/kelurahan', \App\Http\Controllers\KelurahanV2Controller::class)->names('kelurahan_v2')->except(['index', 'show']);
        Route::resource('appv2/layanan', \App\Http\Controllers\LayananController::class)->names('layanan')->except(['index', 'show']);

        // Masjid Write
        Route::post('appv2/skt-masjid/import', [\App\Http\Controllers\SktMasjidController::class, 'import'])->name('skt_masjid.import');
        Route::post('appv2/skt-masjid/upload-skt', [\App\Http\Controllers\SktMasjidController::class, 'uploadSkt'])->name('skt_masjid.upload_skt');
        Route::delete('appv2/skt-masjid/delete-skt/{id}', [\App\Http\Controllers\SktMasjidController::class, 'deleteSkt'])->name('skt_masjid.delete_skt');
        Route::resource('appv2/skt-masjid', \App\Http\Controllers\SktMasjidController::class)->names('skt_masjid')->except(['index', 'show']);

        // Mushalla Write
        Route::post('appv2/skt-mushalla/import', [\App\Http\Controllers\SktMushallaController::class, 'import'])->name('skt_mushalla.import');
        Route::post('appv2/skt-mushalla/upload-skt', [\App\Http\Controllers\SktMushallaController::class, 'uploadSkt'])->name('skt_mushalla.upload_skt');
        Route::delete('appv2/skt-mushalla/delete-skt/{id}', [\App\Http\Controllers\SktMushallaController::class, 'deleteSkt'])->name('skt_mushalla.delete_skt');
        Route::resource('appv2/skt-mushalla', \App\Http\Controllers\SktMushallaController::class)->names('skt_mushalla')->except(['index', 'show']);

        // Marbot Write (Confined to Admin/Operator/Editor)
        Route::post('appv2/marbot/import', [\App\Http\Controllers\MarbotController::class, 'import'])->name('marbot.import');
        Route::get('appv2/marbot/export', [\App\Http\Controllers\MarbotController::class, 'export'])->name('marbot.export');
        Route::get('appv2/marbot/template', [\App\Http\Controllers\MarbotController::class, 'downloadTemplate'])->name('marbot.template');

        // Seleksi Umroh
        Route::get('appv2/marbot/seleksi', [\App\Http\Controllers\MarbotController::class, 'seleksi'])->name('marbot.seleksi');
        Route::get('appv2/marbot/seleksi/export', [\App\Http\Controllers\MarbotController::class, 'exportUmroh'])->name('marbot.seleksi.export');
        Route::post('appv2/marbot/seleksi/proses', [\App\Http\Controllers\MarbotController::class, 'prosesSeleksi'])->name('marbot.seleksi.proses');
        // Insentif
        Route::post('appv2/marbot/insentif', [\App\Http\Controllers\MarbotController::class, 'processInsentif'])->name('marbot.insentif.process');
        Route::post('appv2/marbot/check_deadline', [\App\Http\Controllers\MarbotController::class, 'checkDeadline'])->name('marbot.check_deadline');

        Route::resource('appv2/marbot', \App\Http\Controllers\MarbotController::class)->names('marbot');
    });

    /*
    |--------------------------------------------------------------------------
    | READ-ONLY ACCESS (Admin, Editor, Operator, Viewer)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:Admin,Editor,Operator,Viewer'])->group(function () {

        // SKT Piagam MT V2 (Read Only)
        Route::get('/skt-piagam-mt-v2', [\App\Http\Controllers\SktpiagammtV2Controller::class, 'index'])->name('skt_piagam_mt_v2.index');
        Route::get('/skt-piagam-mt-v2/rekap', [\App\Http\Controllers\SktpiagammtV2Controller::class, 'rekap'])->name('skt_piagam_mt_v2.rekap');
        Route::get('/skt-piagam-mt-v2/trash', [\App\Http\Controllers\SktpiagammtV2Controller::class, 'trash'])->name('skt_piagam_mt_v2.trash');
        Route::get('/skt-piagam-mt-v2/export', [\App\Http\Controllers\SktpiagammtV2Controller::class, 'export'])->name('skt_piagam_mt_v2.export');
        Route::get('/skt-piagam-mt-v2/{skt_piagam_mt}', [\App\Http\Controllers\SktpiagammtV2Controller::class, 'show'])->name('skt_piagam_mt_v2.show');

        // Masjid (Read Only)
        Route::get('appv2/skt-masjid/rekap', [\App\Http\Controllers\SktMasjidController::class, 'rekap'])->name('skt_masjid.rekap');
        Route::get('appv2/skt-masjid/export', [\App\Http\Controllers\SktMasjidController::class, 'export'])->name('skt_masjid.export');
        Route::get('appv2/skt-masjid/template', [\App\Http\Controllers\SktMasjidController::class, 'downloadTemplate'])->name('skt_masjid.template');
        Route::get('appv2/skt-masjid/{id}/cetak-skt', [\App\Http\Controllers\SktMasjidController::class, 'cetakSkt'])->name('skt_masjid.cetak_skt');
        Route::get('appv2/skt-masjid/{id}/cetak-rekomendasi', [\App\Http\Controllers\SktMasjidController::class, 'cetakRekomendasi'])->name('skt_masjid.cetak_rekomendasi');
        Route::get('appv2/skt-masjid', [\App\Http\Controllers\SktMasjidController::class, 'index'])->name('skt_masjid.index');
        Route::get('appv2/skt-masjid/{skt_masjid}', [\App\Http\Controllers\SktMasjidController::class, 'show'])->name('skt_masjid.show');

        // Mushalla (Read Only)
        Route::get('appv2/skt-mushalla/rekap', [\App\Http\Controllers\SktMushallaController::class, 'rekap'])->name('skt_mushalla.rekap');
        Route::get('appv2/skt-mushalla/export', [\App\Http\Controllers\SktMushallaController::class, 'export'])->name('skt_mushalla.export');
        Route::get('appv2/skt-mushalla/template', [\App\Http\Controllers\SktMushallaController::class, 'downloadTemplate'])->name('skt_mushalla.template');
        Route::get('appv2/skt-mushalla/{id}/cetak-skt', [\App\Http\Controllers\SktMushallaController::class, 'cetakSkt'])->name('skt_mushalla.cetak_skt');
        Route::get('appv2/skt-mushalla', [\App\Http\Controllers\SktMushallaController::class, 'index'])->name('skt_mushalla.index');
        Route::get('appv2/skt-mushalla/{skt_mushalla}', [\App\Http\Controllers\SktMushallaController::class, 'show'])->name('skt_mushalla.show');

        // Data Master Read Only
        Route::get('appv2/pegawai', [\App\Http\Controllers\PegawaiController::class, 'index'])->name('pegawai.index');
        Route::get('appv2/kecamatan', [\App\Http\Controllers\KecamatanV2Controller::class, 'index'])->name('kecamatan_v2.index');
        Route::get('appv2/kelurahan', [\App\Http\Controllers\KelurahanV2Controller::class, 'index'])->name('kelurahan_v2.index');
        Route::get('appv2/kelurahan/{id}', [\App\Http\Controllers\KelurahanV2Controller::class, 'show'])->name('kelurahan_v2.show');
        Route::get('appv2/kecamatan/{id}', [\App\Http\Controllers\KecamatanV2Controller::class, 'show'])->name('kecamatan_v2.show');
        Route::get('appv2/layanan', [\App\Http\Controllers\LayananController::class, 'index'])->name('layanan.index');
    });

    /*
    |--------------------------------------------------------------------------
    | LEGACY / OTHER ROUTES
    |--------------------------------------------------------------------------
    */

    // Route Cetak (Old Controller SktpiagammtController) - needed?
    // Kept but secured.
    Route::get('/skt-piagam-mt/{id}/cetak-skt', [SktpiagammtController::class, 'cetakSkt'])->name('skt_piagam_mt.cetak_skt');
    Route::get('/skt-piagam-mt/{id}/cetak-piagam', [SktpiagammtController::class, 'cetakPiagam'])->name('skt_piagam_mt.cetak_piagam');
    Route::get('/skt-piagam-mt/{id}/preview-piagam', [SktpiagammtController::class, 'previewPiagam'])->name('skt_piagam_mt.preview_piagam');

    Route::get('/get-kelurahan', [SktpiagammtController::class, 'getKelurahan'])->name('get.kelurahan');
    Route::get('/get-next-nomor-statistik', [SktpiagammtController::class, 'getNextNomorStatistik'])->name('get.next.nomor.statistik');

    // Secure Import/Export Legacy
    Route::middleware(['role:Admin,Editor,Operator'])->group(function () {
        Route::post('/skt-piagam-mt/import', [SktpiagammtController::class, 'import'])->name('skt_piagam_mt.import');
        Route::post('/skt-piagam-mt/export', [SktpiagammtController::class, 'export'])->name('skt_piagam_mt.export');
        Route::get('/skt-piagam-mt/template', [SktpiagammtController::class, 'downloadTemplate'])->name('skt_piagam_mt.template');
    });

    // API Public (inside Auth)

    // Notifications
    Route::get('/notifications/data', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::get('/notifications/{id}/go', [\App\Http\Controllers\NotificationController::class, 'readAndRedirect'])->name('notifications.go');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.mark_all_read');

    // DEV Only: Sync Notifications (Remove in production)
    Route::get('/dev/sync-notifications', function () {
        // Clear existing (optional, careful!)
        // \Illuminate\Support\Facades\DB::table('notifications')->delete();

        // 1. Sync Marbot Diajukan
        $marbots = \App\Models\Marbot::where('status', 'diajukan')->get();
        $users = \App\Models\User::all(); // Notify ALL users for now (or filter by role)

        $count = 0;
        foreach ($marbots as $m) {
            foreach ($users as $u) {
                // Check if not already notified
                $exists = $u->notifications()
                    ->where('type', 'App\Notifications\NewMarbotNotification')
                    ->whereJsonContains('data->uuid', $m->uuid)
                    ->exists();

                if (! $exists) {
                    $u->notify(new \App\Notifications\NewMarbotNotification($m));
                    $count++;
                }
            }
        }

        return "Synced $count Marbot notifications.";
    });

});
