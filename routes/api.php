<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OnesenderController;
use App\Http\Controllers\WatsapController;
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
Route::post('response', [OnesenderController::class, 'index']);
Route::get('response', [OnesenderController::class, 'index']);
Route::post('watsap-id', [WatsapController::class, 'index']);
Route::get('watsap-id', [WatsapController::class, 'index']);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
