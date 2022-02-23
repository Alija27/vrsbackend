<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::resource('users', \App\Http\Controllers\API\Admin\UserController::class);
Route::resource('vendors',\App\Http\Controllers\API\Admin\VendorController::class);
Route::resource('types',\App\Http\Controllers\API\Admin\TypeCOntroller::class);
