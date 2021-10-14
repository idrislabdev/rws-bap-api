<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Master\PengaturanController;
use App\Http\Controllers\Master\PenggunaController;
use App\Http\Controllers\Master\WilayahController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Transaksi\BaNewLinkController;
use App\Http\Controllers\Transaksi\BeritaAcaraController;
use App\Http\Controllers\Transaksi\DualHomingController;
use App\Http\Controllers\Transaksi\EvidentController;
use App\Http\Controllers\Transaksi\ImageController;
use App\Http\Controllers\Transaksi\LvController;
use App\Http\Controllers\Transaksi\NewLinkController;
use App\Http\Controllers\Transaksi\QcController;
use App\Http\Controllers\Transaksi\UpgradeController;
use App\Http\Controllers\Transaksi\WoController;

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
    // Route::get('refresh', 'AuthController@refresh');    
    Route::group(['middleware' => 'auth:api'], function(){
        Route::get('user',  [AuthController::class, 'user']);  
        Route::post('change-password',  [AuthController::class, 'changePassword']);  

        // Route::post('logout', 'AuthController@logout');
        // Route::post('change-password', 'AuthController@changePassword');
    });
});

Route::prefix('data')->group(function(){
    Route::group(['middleware' => 'auth:api'], function(){
        Route::group(['middleware' => 'isRootOrAdminOrRWS'], function(){
            Route::resource('pengaturan', PengaturanController::class);
            Route::resource('pengguna', PenggunaController::class);
            Route::resource('wilayah', WilayahController::class);
        });
    });
});

Route::prefix('transaksi')->group(function(){
    Route::group(['middleware' => [ 'auth:api', 'optimizeImages']], function(){
        
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

       
        Route::group(['middleware' => 'isMSO'], function(){
            
            Route::get('evident/qc/{wo_id}/site/{wo_site_id}', [QcController::class, 'index']);
            Route::post('evident/qc/{wo_id}/site/{wo_site_id}', [QcController::class, 'store']);
            Route::patch('evident/qc/{wo_id}/site/{wo_site_id}', [QcController::class, 'update']);

            Route::get('evident/lv/{wo_id}/site/{wo_site_id}', [LvController::class, 'index']);     
            Route::post('evident/lv/{wo_id}/site/{wo_site_id}', [LvController::class, 'store']);
            Route::patch('evident/lv/{wo_id}/site/{wo_site_id}', [LvController::class, 'update']);

            
        });

        // Route::group(['middleware' => ['isWITEL', 'optimizeImages']], function(){
            
        //     Route::get('evident/image/{wo_id}/site/{wo_site_id}', [ImageController::class, 'index']);
        //     Route::post('evident/image/{wo_id}/site/{wo_site_id}', [ImageController::class, 'store']);
        //     Route::get('evident/image/{wo_id}/site/{wo_site_id}/data/{id}', [ImageController::class, 'show']);
        //     Route::delete('evident/image/{wo_id}/site/{wo_site_id}/data/{id}', [ImageController::class, 'destroy']);
            
        // });

        Route::group(['middleware' => 'isRootOrAdminOrRWS'], function(){
            // Route::resource('berita-acara', BeritaAcaraController::class);
            Route::get('berita-acara', [BeritaAcaraController::class, 'index']);
            Route::get('berita-acara/{id}/sites', [BeritaAcaraController::class, 'indexSites']);

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
            Route::post('upgrade/create-ba-bypass', [NewLinkController::class, 'createBAByPass']);
            Route::post('upgrade/create-ba/check', [UpgradeController::class, 'checkSiteBA']);
            // Route::get('upgrade/ba/{id}/download', [UpgradeController::class, 'downloadBA']);
            Route::get('upgrade/ba/{id}/refresh', [UpgradeController::class, 'fileBA']);
            Route::delete('upgrade/ba/{id}/delete', [UpgradeController::class, 'deleteBA']);

            Route::post('dual-homing', [DualHomingController::class, 'store']);
            Route::patch('dual-homing/{wo_id}/site/{wo_site_id}', [DualHomingController::class, 'update']);
            Route::post('dual-homing/create-ba', [DualHomingController::class, 'createBA']);
            Route::post('dual-homing/create-ba/check', [DualHomingController::class, 'checkSiteBA']);
            // Route::get('dual-homing/ba/{id}/download', [DualHomingController::class, 'downloadBA']);
            Route::get('dual-homing/ba/{id}/refresh', [DualHomingController::class, 'fileBA']);
            Route::delete('dual-homing/ba/{id}/delete', [DualHomingController::class, 'deleteBA']);

            Route::post('work-order/{id}', [WoController::class, 'update']);
            Route::delete('work-order/{id}', [WoController::class, 'destroy']);

            // Route::prefix('new-link')->group(function(){


               
            // });
            
        });
    });
});

Route::prefix('dashboard')->group(function(){
    Route::group(['middleware' => 'auth:api'], function(){
        Route::get('donut', [DashboardController::class, 'donut']);
        Route::get('list', [DashboardController::class, 'list']);
        Route::get('newlink', [DashboardController::class, 'newlink']);
        Route::get('upgrade', [DashboardController::class, 'upgrade']);
        Route::get('dual-homing', [DashboardController::class, 'dualHoming']);

    });
});

Route::prefix('report')->group(function(){
    Route::group(['middleware' => 'auth:api'], function(){
        Route::get('newlink', [ReportController::class,'newlink']);
        Route::get('upgrade', [ReportController::class,'upgrade']);
        Route::get('dualhoming', [ReportController::class,'dualhoming']);

    });
});

// Route::get('new-link/ba', [BaNewLinkController::class, 'fileBA']);
Route::get('new-link/ba/{id}', [NewLinkController::class, 'fileBA']);
Route::get('dual-homing/ba/{id}', [DualHomingController::class, 'fileBA']);
Route::get('upgrade/ba/{id}', [UpgradeController::class, 'fileBA']);



Route::get('file/{file_name}', [BaNewLinkController::class, 'fileLampiran']);

Route::get('transaksi/new-link/ba/{id}/download', [NewLinkController::class, 'downloadBA']);
Route::get('transaksi/upgrade/ba/{id}/download', [UpgradeController::class, 'downloadBA']);
Route::get('transaksi/dual-homing/ba/{id}/download', [DualHomingController::class, 'downloadBA']);

Route::get('upgrade/ba/{id}/refresh-test', [UpgradeController::class, 'fileBA']);