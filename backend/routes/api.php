<?php
namespace App\Http\Controllers;

use App\Models\Kelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [RegisterController::class, 'register']);
Route::apiResource('/master_atks', MasterAtkController::class);
Route::apiResource('/kelompoks', KelompokController::class);
Route::apiResource('/supliers', SuplierController::class);
Route::apiResource('/details', detailPembelianController::class);
Route::apiResource('/cabangs', CabangController::class);
Route::put('/updateMaster/{master}', [MasterAtkController::class, 'update']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:api');
Route::apiResource('/headers', headerPembelianController::class);
Route::put('/verif/{header}', [headerPembelianController::class, 'verif']);
Route::put('/updateHeader/{nomor}', [headerPembelianController::class, 'update']);
Route::get('/showDetail/{hdr_pm}', [detailPembelianController::class, 'showDetail']);
Route::get('/cariVerif', [headerPembelianController::class, 'cariVerif']);

Route::put('/updateHeaderj/{nomor}', [headerPenjualanController::class, 'update']);
Route::get('/cariVerifjual', [headerPenjualanController::class, 'cariVerif']);
Route::apiResource('/headerjuals', headerPenjualanController::class);
Route::put('/verifjual/{headerjual}', [headerPenjualanController::class, 'verif']);

Route::apiResource('/detailjuals', detailPenjualanController::class);
Route::get('/showDetailjual/{hdr_pn}', [detailPenjualanController::class, 'showDetail']);

Route::get('/laporan', [LaporanController::class, 'getDataLaporan']);
Route::get('/laporanByTglBarangBeli', [LaporanController::class, 'getDataByTanggalNamaBarangBeli']);
Route::get('/laporanByTglBarangJual', [LaporanController::class, 'getDataByTanggalNamaBarangJual']);
