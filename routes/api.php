<?php

use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\PermintaanController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('login', [UserController::class,'login']);

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('users', [UserController::class,'getAllUser']);
    Route::get('cek-bearer', [UserController::class,'cekBearer']);

    Route::put("update-permintaan",[PermintaanController::class,'updatePost']);
    Route::resource('permintaan', PermintaanController::class);
    Route::resource('barang', BarangController::class);

});
