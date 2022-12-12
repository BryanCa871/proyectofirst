<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;

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

Route::prefix("/login")->group(function()
{
    Route::post("/make/registrar",[LoginController::class,"registrar"]);
    Route::post("/make/login",[LoginController::class,"createLogin"]);
    Route::middleware('auth:sanctum')->post("/make/logout",[LoginController::class,"logout"]);
}
);


