<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CNOP\DashboardController;
use App\Http\Controllers\CNOP\ReportController;
use App\Http\Controllers\CNOP\Transaksi\BaNewLinkController;
use App\Http\Controllers\CNOP\Transaksi\BeritaAcaraController;
use App\Http\Controllers\CNOP\Transaksi\DismantleController;
use App\Http\Controllers\CNOP\Transaksi\DualHomingController;
use App\Http\Controllers\CNOP\Transaksi\ImageController;
use App\Http\Controllers\CNOP\Transaksi\LvController;
use App\Http\Controllers\CNOP\Transaksi\NewLinkController;
use App\Http\Controllers\CNOP\Transaksi\QcController;
use App\Http\Controllers\CNOP\Transaksi\RelokasiController;
use App\Http\Controllers\CNOP\Transaksi\UpgradeController;
use App\Http\Controllers\CNOP\Transaksi\WoController;
use App\Http\Controllers\CNOP\Transaksi\DraftOtherTransactionController;
use App\Http\Controllers\CNOP\Transaksi\OtherTransactionController;
use App\Http\Controllers\Master\NomorDokumenController;
use App\Http\Controllers\Master\OloJenisAddOnController;
use App\Http\Controllers\Master\OloJenisOrderController;
use App\Http\Controllers\Master\OloKlienController;
use App\Http\Controllers\Master\OloProdukController;
use App\Http\Controllers\Master\PengaturanController;
use App\Http\Controllers\Master\PenggunaController;
use App\Http\Controllers\Master\WilayahController;
use App\Http\Controllers\OLO\Transaksi\BeritaAcaraController as TransaksiBeritaAcaraController;
use App\Http\Controllers\OLO\Transaksi\DraftBeritaAcaraController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->group(function () {
    Route::post('login',  [AuthController::class, 'login']);
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('user',  [AuthController::class, 'user']);
        Route::post('change-password',  [AuthController::class, 'changePassword']);
    });
});

Route::prefix('data')->group(function () {
    Route::prefix('nomor-dokumen')->group(function () {
        Route::get('', [NomorDokumenController::class, 'index']);
        Route::post('', [NomorDokumenController::class, 'store']);
        Route::get('{id}', [NomorDokumenController::class, 'show']);
        Route::get('check/available', [NomorDokumenController::class, 'check']);
        Route::get('download/dokumen', [NomorDokumenController::class, 'downloadDokumen']);
    });
    Route::resource('pengaturan', PengaturanController::class);
    Route::resource('pengguna', PenggunaController::class);
    Route::resource('wilayah', WilayahController::class);
    Route::resource('olo-jenis-addon', OloJenisAddOnController::class);
    Route::resource('olo-jenis-order', OloJenisOrderController::class);
    Route::resource('olo-klien', OloKlienController::class);
    Route::resource('olo-produk', OloProdukController::class);
    Route::get('telkomsel', [OloKlienController::class, 'telkomselClient']);
    Route::get('telkomsel-jenis-order', [OloJenisOrderController::class, 'telkomselJenisOrder']);
    Route::get('telkomsel-produk', [OloProdukController::class, 'telkomselProduk']);

});

Route::prefix('cnop')->group(function () {

    Route::prefix('transaksi')->group(function () {
        Route::group(['middleware' => ['auth:api', 'optimizeImages']], function () {

            Route::get('work-order/{id}', [WoController::class, 'show']);

            Route::get('new-link', [NewLinkController::class, 'index']);
            Route::get('new-link/{wo_id}/site/{wo_site_id}', [NewLinkController::class, 'show']);
            Route::patch('new-link/{wo_id}/site/{wo_site_id}/oa', [NewLinkController::class, 'updateOA']);
            Route::patch('new-link/{wo_id}/site/{wo_site_id}/ogp', [NewLinkController::class, 'backOGP']);
            Route::patch('new-link/{wo_id}/site/{wo_site_id}/bandwidth', [NewLinkController::class, 'updateBW']);
            Route::patch('new-link/{wo_id}/site/{wo_site_id}/ne-type', [NewLinkController::class, 'updateNeType']);

            Route::get('upgrade', [UpgradeController::class, 'index']);
            Route::get('upgrade/{wo_id}/site/{wo_site_id}', [UpgradeController::class, 'show']);
            Route::patch('upgrade/{wo_id}/site/{wo_site_id}/oa', [UpgradeController::class, 'updateOA']);
            Route::patch('upgrade/{wo_id}/site/{wo_site_id}/ogp', [UpgradeController::class, 'backOGP']);
            Route::patch('upgrade/{wo_id}/site/{wo_site_id}/bandwidth', [UpgradeController::class, 'updateBW']);
            Route::patch('upgrade/{wo_id}/site/{wo_site_id}/alpro-site', [UpgradeController::class, 'updateAlproSite']);
            Route::patch('upgrade/{wo_id}/site/{wo_site_id}/keterangan', [UpgradeController::class, 'updateKeterangan']);

            Route::get('relokasi', [RelokasiController::class, 'index']);
            Route::get('relokasi/{wo_id}/site/{wo_site_id}', [RelokasiController::class, 'show']);
            Route::patch('relokasi/{wo_id}/site/{wo_site_id}/oa', [RelokasiController::class, 'updateOA']);
            Route::patch('relokasi/{wo_id}/site/{wo_site_id}/ogp', [RelokasiController::class, 'backOGP']);
            Route::patch('relokasi/{wo_id}/site/{wo_site_id}/bandwidth', [RelokasiController::class, 'updateBW']);
            Route::patch('relokasi/{wo_id}/site/{wo_site_id}/alpro-site', [RelokasiController::class, 'updateAlproSite']);
            Route::patch('relokasi/{wo_id}/site/{wo_site_id}/keterangan', [RelokasiController::class, 'updateKeterangan']);

            Route::get('dismantle', [DismantleController::class, 'index']);
            Route::get('dismantle/{wo_id}/site/{wo_site_id}', [DismantleController::class, 'show']);
            Route::patch('dismantle/{wo_id}/site/{wo_site_id}/deactivate', [DismantleController::class, 'deactivate']);

            Route::get('dual-homing', [DualHomingController::class, 'index']);
            Route::get('dual-homing/{wo_id}/site/{wo_site_id}', [DualHomingController::class, 'show']);
            Route::get('dual-homing/parameter/{wo_id}/site/{wo_site_id}', [DualHomingController::class, 'showParameter']);
            Route::post('dual-homing/parameter/{wo_id}/site/{wo_site_id}', [DualHomingController::class, 'storeParameter']);
            Route::patch('dual-homing/parameter/{wo_id}/site/{wo_site_id}', [DualHomingController::class, 'updateParameter']);
            Route::patch('dual-homing/{wo_id}/site/{wo_site_id}/oa', [DualHomingController::class, 'updateOA']);
            Route::patch('dual-homing/{wo_id}/site/{wo_site_id}/ogp', [DualHomingController::class, 'backOGP']);

            Route::get('evident/image/{wo_id}/site/{wo_site_id}', [ImageController::class, 'index']);
            Route::post('evident/image/{wo_id}/site/{wo_site_id}', [ImageController::class, 'store']);
            Route::get('evident/image/{wo_id}/site/{wo_site_id}/data/{id}', [ImageController::class, 'show']);
            Route::delete('evident/image/{wo_id}/site/{wo_site_id}/data/{id}', [ImageController::class, 'destroy']);

            Route::group(['middleware' => 'isMSO'], function () {

                Route::get('evident/qc/{wo_id}/site/{wo_site_id}', [QcController::class, 'index']);
                Route::post('evident/qc/{wo_id}/site/{wo_site_id}', [QcController::class, 'store']);
                Route::patch('evident/qc/{wo_id}/site/{wo_site_id}', [QcController::class, 'update']);

                Route::get('evident/lv/{wo_id}/site/{wo_site_id}', [LvController::class, 'index']);
                Route::post('evident/lv/{wo_id}/site/{wo_site_id}', [LvController::class, 'store']);
                Route::patch('evident/lv/{wo_id}/site/{wo_site_id}', [LvController::class, 'update']);
            });

            Route::group(['middleware' => 'isRootOrAdminOrRWS'], function () {
                // Route::resource('berita-acara', BeritaAcaraController::class);
                Route::get('berita-acara', [BeritaAcaraController::class, 'index']);
                Route::get('berita-acara/{id}/sites', [BeritaAcaraController::class, 'indexSites']);
                Route::patch('berita-acara/{id}/sirkulir', [BeritaAcaraController::class, 'changeSirkulir']);

                Route::post('new-link', [NewLinkController::class, 'store']);
                Route::patch('new-link/{wo_id}/site/{wo_site_id}', [NewLinkController::class, 'update']);
                Route::post('new-link/create-ba', [NewLinkController::class, 'createBA']);
                Route::post('new-link/create-ba-bypass', [NewLinkController::class, 'createBAByPass']);

                Route::post('new-link/create-ba/check', [NewLinkController::class, 'checkSiteBA']);
                // Route::get('new-link/ba/{id}/download', [NewLinkController::class, 'downloadBA']);
                Route::get('new-link/ba/{id}/refresh', [NewLinkController::class, 'fileBA']);
                Route::delete('new-link/ba/{id}/delete', [NewLinkController::class, 'deleteBA']);


                Route::post('upgrade', [UpgradeController::class, 'store']);
                Route::patch('upgrade/{wo_id}/site/{wo_site_id}', [UpgradeController::class, 'update']);
                Route::post('upgrade/create-ba', [UpgradeController::class, 'createBA']);
                Route::post('upgrade/create-ba-bypass', [UpgradeController::class, 'createBAByPass']);
                Route::post('upgrade/create-ba/check', [UpgradeController::class, 'checkSiteBA']);
                // Route::get('upgrade/ba/{id}/download', [UpgradeController::class, 'downloadBA']);
                Route::get('upgrade/ba/{id}/refresh', [UpgradeController::class, 'fileBA']);
                Route::delete('upgrade/ba/{id}/delete', [UpgradeController::class, 'deleteBA']);

                Route::post('relokasi', [RelokasiController::class, 'store']);
                Route::patch('relokasi/{wo_id}/site/{wo_site_id}', [RelokasiController::class, 'update']);
                Route::post('relokasi/create-ba', [RelokasiController::class, 'createBA']);
                Route::post('relokasi/create-ba-bypass', [RelokasiController::class, 'createBAByPass']);
                Route::post('relokasi/create-ba/check', [RelokasiController::class, 'checkSiteBA']);
                Route::get('relokasi/ba/{id}/refresh', [RelokasiController::class, 'fileBA']);
                Route::delete('relokasi/ba/{id}/delete', [RelokasiController::class, 'deleteBA']);

                Route::post('dismantle', [DismantleController::class, 'store']);
                Route::patch('dismantle/{wo_id}/site/{wo_site_id}', [DismantleController::class, 'update']);
                Route::post('dismantle/create-ba', [DismantleController::class, 'createBA']);
                Route::post('dismantle/create-ba-bypass', [DismantleController::class, 'createBAByPass']);
                Route::post('dismantle/create-ba/check', [DismantleController::class, 'checkSiteBA']);
                Route::get('dismantle/ba/{id}/refresh', [DismantleController::class, 'fileBA']);
                Route::delete('dismantle/ba/{id}/delete', [DismantleController::class, 'deleteBA']);

                Route::post('dual-homing', [DualHomingController::class, 'store']);
                Route::patch('dual-homing/{wo_id}/site/{wo_site_id}', [DualHomingController::class, 'update']);
                Route::post('dual-homing/create-ba', [DualHomingController::class, 'createBA']);
                Route::post('dual-homing/create-ba/check', [DualHomingController::class, 'checkSiteBA']);
                // Route::get('dual-homing/ba/{id}/download', [DualHomingController::class, 'downloadBA']);
                Route::get('dual-homing/ba/{id}/refresh', [DualHomingController::class, 'fileBA']);
                Route::delete('dual-homing/ba/{id}/delete', [DualHomingController::class, 'deleteBA']);

                Route::resource('other-draft', DraftOtherTransactionController::class);
                Route::resource('other', OtherTransactionController::class);
                Route::post('other/check/no-dokumen', [OtherTransactionController::class, 'checkNomor']);
                Route::get('other/{olo_ba_id}/detail/{id}/add-on', [OtherTransactionController::class, 'addOnlist']);
                Route::delete('other/{olo_ba_id}/lampiran/{id}', [OtherTransactionController::class, 'removeLampiran']);
                Route::post('other/{olo_ba_id}/lampiran', [OtherTransactionController::class, 'updateLampiran']);

                Route::post('work-order/{id}', [WoController::class, 'update']);
                Route::delete('work-order/{id}', [WoController::class, 'destroy']);
            });
        });
    });

    Route::prefix('dashboard')->group(function () {
        Route::group(['middleware' => 'auth:api'], function () {
            Route::get('donut', [DashboardController::class, 'donut']);
            Route::get('list', [DashboardController::class, 'list']);
            Route::get('newlink', [DashboardController::class, 'newlink']);
            Route::get('upgrade', [DashboardController::class, 'upgrade']);
            Route::get('dual-homing', [DashboardController::class, 'dualHoming']);
            Route::get('relokasi', [DashboardController::class, 'relokasi']);
        });
    });

    Route::prefix('report')->group(function () {
        Route::group(['middleware' => 'auth:api'], function () {
            Route::get('newlink', [ReportController::class, 'newlink']);
            Route::get('upgrade', [ReportController::class, 'upgrade']);
            Route::get('dualhoming', [ReportController::class, 'dualhoming']);
            Route::get('relokasi', [ReportController::class, 'relokasi']);
            Route::get('other/view', [OtherTransactionController::class, 'reportView']);
            Route::get('other/download', [OtherTransactionController::class, 'reportDownload']);
        });
    });
    

    // Route::get('new-link/ba', [BaNewLinkController::class, 'fileBA']);
    Route::get('new-link/ba/{id}', [NewLinkController::class, 'fileBA']);
    Route::get('dual-homing/ba/{id}', [DualHomingController::class, 'fileBA']);
    Route::get('upgrade/ba/{id}', [UpgradeController::class, 'fileBA']);
    Route::get('upgrade/ba/{id}/refresh-test', [UpgradeController::class, 'fileBA']);
    Route::get('file/{file_name}', [BaNewLinkController::class, 'fileLampiran']);
    Route::get('transaksi/new-link/ba/{id}/download', [NewLinkController::class, 'downloadBA']);
    Route::get('transaksi/upgrade/ba/{id}/download', [UpgradeController::class, 'downloadBA']);
    Route::get('transaksi/relokasi/ba/{id}/download', [RelokasiController::class, 'downloadBA']);
    Route::get('transaksi/dual-homing/ba/{id}/download', [DualHomingController::class, 'downloadBA']);
    Route::get('transaksi/dismantle/ba/{id}/download', [RelokasiController::class, 'downloadBA']);
    Route::get('transaksi/other/download/file/{id}/{tipe}', [OtherTransactionController::class, 'fileBA']);

    Route::get('test-report', [NewLinkController::class, 'testReport']);
});

Route::prefix('olo')->group(function () {
    Route::group(['middleware' => 'auth:api'], function () {

        Route::prefix('transaksi')->group(function () {
            Route::resource('draft-berita-acara', DraftBeritaAcaraController::class);
            Route::resource('berita-acara', TransaksiBeritaAcaraController::class);
            Route::post('berita-acara/check/no-dokumen', [TransaksiBeritaAcaraController::class, 'checkNomor']);
            Route::get('berita-acara/{olo_ba_id}/detail/{id}/add-on', [TransaksiBeritaAcaraController::class, 'addOnlist']);
            Route::delete('berita-acara/{olo_ba_id}/lampiran/{id}', [TransaksiBeritaAcaraController::class, 'removeLampiran']);
            Route::post('berita-acara/{olo_ba_id}/lampiran', [TransaksiBeritaAcaraController::class, 'updateLampiran']);
        });


        Route::prefix('report')->group(function () {
            Route::get('view', [TransaksiBeritaAcaraController::class, 'reportView']);
            Route::get('download', [TransaksiBeritaAcaraController::class, 'reportDownload']);
        });
    });
    Route::get('transaksi/berita-acara/download/file/{id}/{tipe}', [TransaksiBeritaAcaraController::class, 'fileBA']);
    Route::get('file/{file_name}', [TransaksiBeritaAcaraController::class, 'fileLampiran']);
});
