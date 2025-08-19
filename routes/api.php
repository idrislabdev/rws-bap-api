<?php

use App\Http\Controllers\AccountCenter\DashboardController as AccountCenterDashboardController;
use App\Http\Controllers\AccountCenter\JabatanController;
use App\Http\Controllers\AccountCenter\ProfileNcxController;
use App\Http\Controllers\AccountCenter\ProfileStarclickController;
use App\Http\Controllers\AccountCenter\Transaksi\HistoryPengajuanAplikasiController;
use App\Http\Controllers\AccountCenter\Transaksi\PengajuanAplikasiController;
use App\Http\Controllers\AccountCenter\UserAccountController;
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
use App\Http\Controllers\CNOP\Transaksi\FrontHaulController;
use App\Http\Controllers\CNOP\Transaksi\OtherTransactionController;
use App\Http\Controllers\Master\NomorDokumenController;
use App\Http\Controllers\Master\OloJenisAddOnController;
use App\Http\Controllers\Master\OloJenisOrderController;
use App\Http\Controllers\Master\OloKlienController;
use App\Http\Controllers\Master\OloProdukController;
use App\Http\Controllers\Master\PengaturanController;
use App\Http\Controllers\Master\PenggunaController;
use App\Http\Controllers\Master\PeranController;
use App\Http\Controllers\Master\SarpenTemplateController;
use App\Http\Controllers\Master\SiteController;
use App\Http\Controllers\Master\StoController;
use App\Http\Controllers\Master\WilayahController;
use App\Http\Controllers\OLO\Transaksi\BeritaAcaraController as TransaksiBeritaAcaraController;
use App\Http\Controllers\OLO\Transaksi\DraftBeritaAcaraController;
use App\Http\Controllers\SARPEN\DashboardController as SARPENDashboardController;
use App\Http\Controllers\SARPEN\ReportController as SARPENReportController;
use App\Http\Controllers\SARPEN\TargetController;
use App\Http\Controllers\SARPEN\Transaksi\BeritaAcaraController as SARPENTransaksiBeritaAcaraController;
use App\Http\Controllers\TIF\Transaksi\BeritaAcaraController as TIFTransaksiBeritaAcaraController;
use App\Http\Controllers\TIF\Transaksi\DraftBeritaAcaraController as TransaksiDraftBeritaAcaraController;

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
        Route::patch('update-profile',  [AuthController::class, 'updateProfile']);  
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
    Route::group(['middleware' => 'auth:api'], function () {
        Route::resource('pengaturan', PengaturanController::class);
        Route::resource('peran', PeranController::class);
        Route::resource('pengguna', PenggunaController::class);
        Route::resource('wilayah', WilayahController::class);
        Route::resource('olo-jenis-addon', OloJenisAddOnController::class);
        Route::resource('olo-jenis-order', OloJenisOrderController::class);
        Route::resource('olo-klien', OloKlienController::class);
        Route::resource('olo-produk', OloProdukController::class);
        Route::resource('sto', StoController::class);
        Route::resource('site', SiteController::class);
        Route::resource('sarpen-template', SarpenTemplateController::class);

        Route::get('telkomsel', [OloKlienController::class, 'telkomselClient']);
        Route::get('telkomsel-jenis-order', [OloJenisOrderController::class, 'telkomselJenisOrder']);
        Route::get('telkomsel-produk', [OloProdukController::class, 'telkomselProduk']);

        Route::get('perans', [PeranController::class, 'getAll']);
        Route::get('pengguna/pejabat/sarpen', [PenggunaController::class, 'pejabatSarpen']);
        Route::get('sarpen-template/preview/{id}', [SarpenTemplateController::class, 'preview']);
        Route::get('sarpen-template/group/{group}', [SarpenTemplateController::class, 'dataTemplate']);


    });
    Route::get('pengguna/ttd/{file_name}', [PenggunaController::class, 'fileTTD']);
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

            Route::get('fronthaul', [FrontHaulController::class, 'index']);
            Route::get('fronthaul/{wo_id}/site/{wo_site_id}', [FrontHaulController::class, 'show']);

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
                Route::patch('new-link/ba/{id}/paraf', [NewLinkController::class, 'parafWholesale']);
                Route::patch('new-link/ba/{id}/ttd', [NewLinkController::class, 'ttdWholesale']);


                Route::post('upgrade', [UpgradeController::class, 'store']);
                Route::patch('upgrade/{wo_id}/site/{wo_site_id}', [UpgradeController::class, 'update']);
                Route::post('upgrade/create-ba', [UpgradeController::class, 'createBA']);
                Route::post('upgrade/create-ba-bypass', [UpgradeController::class, 'createBAByPass']);
                Route::post('upgrade/create-ba/check', [UpgradeController::class, 'checkSiteBA']);
                // Route::get('upgrade/ba/{id}/download', [UpgradeController::class, 'downloadBA']);
                Route::get('upgrade/ba/{id}/refresh', [UpgradeController::class, 'fileBA']);
                Route::delete('upgrade/ba/{id}/delete', [UpgradeController::class, 'deleteBA']);
                Route::patch('upgrade/ba/{id}/paraf', [UpgradeController::class, 'parafWholesale']);
                Route::patch('upgrade/ba/{id}/ttd', [UpgradeController::class, 'ttdWholesale']);

                Route::post('relokasi', [RelokasiController::class, 'store']);
                Route::patch('relokasi/{wo_id}/site/{wo_site_id}', [RelokasiController::class, 'update']);
                Route::post('relokasi/create-ba', [RelokasiController::class, 'createBA']);
                Route::post('relokasi/create-ba-bypass', [RelokasiController::class, 'createBAByPass']);
                Route::post('relokasi/create-ba/check', [RelokasiController::class, 'checkSiteBA']);
                Route::get('relokasi/ba/{id}/refresh', [RelokasiController::class, 'fileBA']);
                Route::delete('relokasi/ba/{id}/delete', [RelokasiController::class, 'deleteBA']);
                Route::patch('relokasi/ba/{id}/paraf', [RelokasiController::class, 'parafWholesale']);
                Route::patch('relokasi/ba/{id}/ttd', [RelokasiController::class, 'ttdWholesale']);

                Route::post('dismantle', [DismantleController::class, 'store']);
                Route::patch('dismantle/{wo_id}/site/{wo_site_id}', [DismantleController::class, 'update']);
                Route::post('dismantle/create-ba', [DismantleController::class, 'createBA']);
                Route::post('dismantle/create-ba-bypass', [DismantleController::class, 'createBAByPass']);
                Route::post('dismantle/create-ba/check', [DismantleController::class, 'checkSiteBA']);
                Route::get('dismantle/ba/{id}/refresh', [DismantleController::class, 'fileBA']);
                Route::delete('dismantle/ba/{id}/delete', [DismantleController::class, 'deleteBA']);
                Route::patch('dismantle/ba/{id}/ttd', [DismantleController::class, 'ttdWholesale']);

                Route::post('dual-homing', [DualHomingController::class, 'store']);
                Route::patch('dual-homing/{wo_id}/site/{wo_site_id}', [DualHomingController::class, 'update']);
                Route::post('dual-homing/create-ba', [DualHomingController::class, 'createBA']);
                Route::post('dual-homing/create-ba/check', [DualHomingController::class, 'checkSiteBA']);
                // Route::get('dual-homing/ba/{id}/download', [DualHomingController::class, 'downloadBA']);
                Route::get('dual-homing/ba/{id}/refresh', [DualHomingController::class, 'fileBA']);
                Route::delete('dual-homing/ba/{id}/delete', [DualHomingController::class, 'deleteBA']);

                Route::post('fronthaul', [FrontHaulController::class, 'store']);
                Route::patch('fronthaul/{wo_id}/site/{wo_site_id}', [FrontHaulController::class, 'update']);
                Route::post('fronthaul/create-ba', [FrontHaulController::class, 'createBA']);
                Route::post('fronthaul/create-ba/check', [FrontHaulController::class, 'checkSiteBA']);
                // Route::get('fronthaul/ba/{id}/download', [FrontHaulController::class, 'downloadBA']);
                Route::get('fronthaul/ba/{id}/refresh', [FrontHaulController::class, 'fileBA']);
                Route::delete('fronthaul/ba/{id}/delete', [FrontHaulController::class, 'deleteBA']);
                Route::patch('fronthaul/ba/{id}/paraf', [FrontHaulController::class, 'parafWholesale']);
                Route::patch('fronthaul/ba/{id}/ttd', [FrontHaulController::class, 'ttdWholesale']);

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
            Route::get('fronthaul', [DashboardController::class, 'fronthaul']);
        });
    });

    Route::prefix('report')->group(function () {
        Route::group(['middleware' => 'auth:api'], function () {
            Route::get('newlink', [ReportController::class, 'newlink']);
            Route::get('upgrade', [ReportController::class, 'upgrade']);
            Route::get('dualhoming', [ReportController::class, 'dualhoming']);
            Route::get('relokasi', [ReportController::class, 'relokasi']);
            Route::get('fronthaul', [ReportController::class, 'fronthaul']);
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
    Route::get('transaksi/fronthaul/ba/{id}/download', [UpgradeController::class, 'downloadBA']);
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
            Route::patch('berita-acara/{olo_ba_id}/paraf', [TransaksiBeritaAcaraController::class, 'parafWholesale']);
            Route::patch('berita-acara/{olo_ba_id}/ttd', [TransaksiBeritaAcaraController::class, 'ttdWholesale']);
            Route::patch('berita-acara/{olo_ba_id}/upload', [TransaksiBeritaAcaraController::class, 'uploadDokumen']);
            Route::get('berita-acara/{name}/dokumen-sirkulir', [TransaksiBeritaAcaraController::class, 'dokumenSirkulir']);

        });


        Route::prefix('report')->group(function () {
            Route::get('view', [TransaksiBeritaAcaraController::class, 'reportView']);
            Route::get('download', [TransaksiBeritaAcaraController::class, 'reportDownload']);
        });
    });
    Route::get('transaksi/berita-acara/download/file/{id}/{tipe}', [TransaksiBeritaAcaraController::class, 'fileBA']);
    Route::get('file/{file_name}', [TransaksiBeritaAcaraController::class, 'fileLampiran']);
});

Route::prefix('sarpen')->group(function () {
    Route::group(['middleware' => 'auth:api'], function () {

        Route::prefix('transaksi')->group(function () {
            Route::group(['middleware' => ['auth:api', 'optimizeImages']], function () {
                Route::resource('berita-acara', SARPENTransaksiBeritaAcaraController::class);
                Route::get('berita-acara/total', [SARPENTransaksiBeritaAcaraController::class, 'totalSarpen']);
                Route::patch('berita-acara/{id}/proposed', [SARPENTransaksiBeritaAcaraController::class, 'proposed']);
                Route::patch('berita-acara/{id}/ttd_witel', [SARPENTransaksiBeritaAcaraController::class, 'ttdWitel']);
                Route::patch('berita-acara/{id}/paraf_wholesale', [SARPENTransaksiBeritaAcaraController::class, 'parafWholesale']);
                Route::patch('berita-acara/{id}/ttd_wholesale', [SARPENTransaksiBeritaAcaraController::class, 'ttdWholesale']);
                Route::patch('berita-acara/{id}/rejected', [SARPENTransaksiBeritaAcaraController::class, 'rejected']);
                Route::patch('berita-acara/{id}/upload-dokumen', [SARPENTransaksiBeritaAcaraController::class, 'uploadDokumen']);
                Route::patch('berita-acara/bulk/proses', [SARPENTransaksiBeritaAcaraController::class, 'bulkProses']);
                Route::post('berita-acara/{id}/gambar', [SARPENTransaksiBeritaAcaraController::class, 'uploadGambar']);
                Route::delete('berita-acara/{id}/gambar/{no}', [SARPENTransaksiBeritaAcaraController::class, 'deleteGambar']);
            });
        });

        Route::prefix('dashboard')->group(function () {
            Route::group(['middleware' => 'auth:api'], function () {
                Route::get('all', [SARPENDashboardController::class, 'all']);
                Route::get('donut', [SARPENDashboardController::class, 'donut']);
                Route::get('column', [SARPENDashboardController::class, 'column']);
                Route::get('ba-sesuai-witel', [SARPENDashboardController::class, 'baSesuaiWitel']);
                Route::get('target-sesuai-witel', [SARPENDashboardController::class, 'targetSesuaiWitel']);
                Route::get('active-target', [SARPENDashboardController::class, 'activeTarget']);

            });
        });
        Route::prefix('report')->group(function () {
            Route::group(['middleware' => 'auth:api'], function () {
                Route::get('ba-sesuai-witel', [SARPENReportController::class, 'baSesuaiWitel']);
                Route::get('ba-dashboard', [SARPENReportController::class, 'baDashboard']);
                Route::get('target-sarpen', [SARPENReportController::class, 'targetSarpen']);
            });
        });

        Route::prefix('data')->group(function () {
            Route::get('berita-acara/total/{group}', [SARPENTransaksiBeritaAcaraController::class, 'totalSarpen']);
            Route::get('target', [TargetController::class, 'dataTarget']);

        });

        Route::prefix('view')->group(function () {
            Route::get('berita-acara/{id}', [SARPENTransaksiBeritaAcaraController::class, 'preview']);
            Route::get('berita-acara-sirkulir/{name}', [SARPENTransaksiBeritaAcaraController::class, 'dokumenSirkulir']);
        });

        Route::prefix('target')->group(function () {
            Route::get('', [TargetController::class, 'index']);
            Route::post('', [TargetController::class, 'store']);
            Route::delete('{id}', [TargetController::class, 'destroy']);
            Route::get('{id}', [TargetController::class, 'show']);
            Route::patch('{id}/close-target', [TargetController::class, 'closeTarget']);
            Route::post('{target_id}/no/{no}', [TargetController::class, 'addWitelDetail']);
            Route::post('{target_id}/bulk', [TargetController::class, 'bulkUpload']);
            Route::delete('{target_id}/no/{detail_no}/detail/{no}', [TargetController::class, 'deleteWitelDetail']);
            Route::get('download/template', [TargetController::class, 'downloadTemplate']);
        });

    });
    Route::get('gambar/{name}', [SARPENTransaksiBeritaAcaraController::class, 'gambarSarpen']);

});

Route::prefix('account-center')->group(function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::resource('profile-ncx', ProfileNcxController::class);
        Route::resource('profile-starclick', ProfileStarclickController::class);
        Route::resource('jabatan', JabatanController::class);
        Route::resource('user-account', UserAccountController::class);
    });
    Route::prefix('transaksi')->group(function () {
        Route::group(['middleware' => ['auth:api', 'optimizeImages']], function () {
            Route::get('pengajuan-aplikasi', [PengajuanAplikasiController::class, 'index']);
            Route::post('pengajuan-aplikasi', [PengajuanAplikasiController::class, 'store']);
            Route::patch('pengajuan-aplikasi/{id}', [PengajuanAplikasiController::class, 'update']);
            Route::get('pengajuan-aplikasi/{id}', [PengajuanAplikasiController::class, 'show']);
            Route::patch('pengajuan-aplikasi/{id}/update-status', [PengajuanAplikasiController::class, 'updateStatus']);
            Route::patch('pengajuan-aplikasi/bulk/proses', [PengajuanAplikasiController::class, 'bulkProses']);
            Route::patch('pengajuan-aplikasi/proses/{type}', [PengajuanAplikasiController::class, 'prosesAplikasi']);

            Route::resource('history', HistoryPengajuanAplikasiController::class);
            Route::get('history/download/{aplikasi}/{history_id}', [HistoryPengajuanAplikasiController::class, 'downloadPengajuan']);
            Route::get('history/download-zip/{aplikasi}/{history_id}', [HistoryPengajuanAplikasiController::class, 'downloadZip']);
        });
        // Route::get('history/download-zip/{aplikasi}/{history_id}', [HistoryPengajuanAplikasiController::class, 'downloadZip']);

    });
    Route::prefix('dashboard')->group(function () {
        Route::get('summary-witel', [AccountCenterDashboardController::class, 'summaryWitel']);
        Route::get('summary-witel/user/{aplikasi}', [AccountCenterDashboardController::class, 'userAccount']);
        Route::get('summary-witel/pengajuan/{aplikasi}', [AccountCenterDashboardController::class, 'pengajuanUser']);
        Route::get('summary-witel/user/{aplikasi}/download', [AccountCenterDashboardController::class, 'downloadUser']);
        Route::get('summary-witel/pengajuan/{aplikasi}/download', [AccountCenterDashboardController::class, 'downloadPengajuan']);

    });
    Route::group(['middleware' => ['auth:api', 'optimizeImages']], function () {
        Route::get('file-pakta/{name}', [PengajuanAplikasiController::class, 'filePakta']);
        Route::get('nota-dinas/{name}', [HistoryPengajuanAplikasiController::class, 'downloadNotaDinas']);
        Route::get('feedback-dit/{name}', [HistoryPengajuanAplikasiController::class, 'downloadFeedbackDIT']);
    });
    
    Route::get('image-ktp/{name}', [PengajuanAplikasiController::class, 'imageKtp']);
});

Route::prefix('olo-tif')->group(function () {
    Route::group(['middleware' => 'auth:api'], function () {

        Route::prefix('transaksi')->group(function () {
            Route::resource('draft-berita-acara', TransaksiDraftBeritaAcaraController::class);
            Route::resource('berita-acara', TIFTransaksiBeritaAcaraController::class);
            Route::post('berita-acara/check/no-dokumen', [TIFTransaksiBeritaAcaraController::class, 'checkNomor']);
            Route::get('berita-acara/{olo_ba_id}/detail/{id}/add-on', [TIFTransaksiBeritaAcaraController::class, 'addOnlist']);
            Route::delete('berita-acara/{olo_ba_id}/lampiran/{id}', [TIFTransaksiBeritaAcaraController::class, 'removeLampiran']);
            Route::post('berita-acara/{olo_ba_id}/lampiran', [TIFTransaksiBeritaAcaraController::class, 'updateLampiran']);
            Route::patch('berita-acara/{olo_ba_id}/paraf', [TIFTransaksiBeritaAcaraController::class, 'parafWholesale']);
            Route::patch('berita-acara/{olo_ba_id}/ttd', [TIFTransaksiBeritaAcaraController::class, 'ttdWholesale']);
            Route::patch('berita-acara/{olo_ba_id}/upload', [TIFTransaksiBeritaAcaraController::class, 'uploadDokumen']);
            Route::get('berita-acara/{name}/dokumen-sirkulir', [TIFTransaksiBeritaAcaraController::class, 'dokumenSirkulir']);

        });


        Route::prefix('report')->group(function () {
            Route::get('view', [TIFTransaksiBeritaAcaraController::class, 'reportView']);
            Route::get('download', [TIFTransaksiBeritaAcaraController::class, 'reportDownload']);
        });
    });
    Route::get('transaksi/berita-acara/download/file/{id}/{tipe}', [TIFTransaksiBeritaAcaraController::class, 'fileBA']);
    Route::get('file/{file_name}', [TIFTransaksiBeritaAcaraController::class, 'fileLampiran']);
});
