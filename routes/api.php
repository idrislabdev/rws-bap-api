<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Master\PengaturanController;
use App\Http\Controllers\Master\PenggunaController;
use App\Http\Controllers\Master\WilayahController;
use App\Http\Controllers\Transaksi\BaNewLinkController;
use App\Http\Controllers\Transaksi\BeritaAcaraController;
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
    Route::group(['middleware' => 'auth:api'], function(){
        
        Route::get('work-order/{id}', [WoController::class, 'show']);
       
        Route::get('new-link', [NewLinkController::class, 'index']);
        Route::get('new-link/{wo_id}/site/{wo_site_id}', [NewLinkController::class, 'show']);
        Route::patch('new-link/{wo_id}/site/{wo_site_id}/oa', [NewLinkController::class, 'updateOA']);
        Route::patch('new-link/{wo_id}/site/{wo_site_id}/bandwidth', [NewLinkController::class, 'updateBW']);

        Route::get('upgrade', [UpgradeController::class, 'index']);
        Route::get('upgrade/{wo_id}/site/{wo_site_id}', [UpgradeController::class, 'show']);
        Route::patch('upgrade/{wo_id}/site/{wo_site_id}/oa', [NewLinkController::class, 'updateOA']);
        Route::patch('upgrade/{wo_id}/site/{wo_site_id}/bandwidth', [NewLinkController::class, 'updateBW']);

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
            Route::post('new-link/create-ba/check', [NewLinkController::class, 'checkSiteBA']);
            Route::get('new-link/ba/{id}/download', [NewLinkController::class, 'downloadBA']);

            Route::post('upgrade', [UpgradeController::class, 'store']);
            Route::patch('upgrade/{wo_id}/site/{wo_site_id}', [UpgradeController::class, 'update']);
            Route::post('upgrade/create-ba', [UpgradeController::class, 'createBA']);
            Route::post('upgrade/create-ba/check', [UpgradeController::class, 'checkSiteBA']);
            Route::get('upgrade/ba/{id}/download', [UpgradeController::class, 'downloadBA']);

            Route::post('work-order/{id}', [WoController::class, 'update']);
            Route::delete('work-order/{id}', [WoController::class, 'destroy']);

            // Route::prefix('new-link')->group(function(){


               
            // });
            
        });
    });
});

// Route::get('new-link/ba', [BaNewLinkController::class, 'fileBA']);
Route::get('new-link/ba/{id}', [NewLinkController::class, 'fileBA']);


Route::get('file/{file_name}', [BaNewLinkController::class, 'fileLampiran']);
