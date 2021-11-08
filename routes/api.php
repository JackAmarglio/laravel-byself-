<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;

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

Route::post('/resetpassword',[UserAuthController::class, 'sendresetpasswordemail']);
Route::post('/setresetpassword',[UserAuthController::class, 'setresetpassword']);
Route::post('/login', [UserAuthController::class, 'login']);
Route::post('/logout', [UserAuthController::class, 'logout']);
Route::post('/register', [UserAuthController::class, 'register']);
Route::post('/verify', [UserAuthController::class, 'verify']);
Route::middleware('auth:api')->group( function () {
    Route::get('/home', [UserAuthController::class, 'home']);
});
