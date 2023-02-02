<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiAuthMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('welcome');
});


Route::post('/api/register',[App\Http\Controllers\Gener02Controller::class,'register']);
Route::post('/api/login',[App\Http\Controllers\Gener02Controller::class,'login']);
Route::post('/api/taraloja/register',[App\Http\Controllers\TaralojaController::class,'register']);
Route::post('/api/taraloja/getAll_tarloja',[App\Http\Controllers\TaralojaController::class,'getAll_tarloja']);
Route::post('/api/taraloja/getTarifas',[App\Http\Controllers\TaralojaController::class,'getTarifas']);

Route::post('/api/tiposaloja/getAll_tiposaloja',[App\Http\Controllers\TiposalojaController::class,'getAll_tiposaloja']);
Route::post('/api/tiposaloja/register',[App\Http\Controllers\TiposalojaController::class,'register']);


Route::post('/api/subsi15/getCategoria',[App\Http\Controllers\Subsi15Controller::class,'getCategoria']);



