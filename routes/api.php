<?php

use App\Http\Controllers\UserController;
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
Route::get('/utilisateur/inscription', [UserController::class, 'inscription']);
Route::get('/utilisateur/connexion', [UserController::class, 'connexion']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/utilisateur/compte/deconnexion', [UserController::class, 'deconnexion']);
    Route::get('/utilisateur/compte/suppression', [UserController::class, 'suppression']);
 
}
);
