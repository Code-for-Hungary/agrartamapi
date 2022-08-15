<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlapController;
use App\Http\Controllers\ForrasController;
use App\Http\Controllers\JogcimController;
use App\Http\Controllers\MegyeController;
use App\Http\Controllers\CegcsoportController;
use App\Http\Controllers\TamogatottController;
use App\Http\Controllers\EvController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TamogatasOsszegController;

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

Route::post('/search', [SearchController::class, 'index']);
Route::post('/count', [SearchController::class, 'count']);

Route::get('/evs', [EvController::class, 'index']);

Route::get('/alaps', [AlapController::class, 'index']);
Route::get('/alaps/{alap}', [AlapController::class, 'show']);

Route::get('/forras', [ForrasController::class, 'index']);
Route::get('/forras/{forras}', [ForrasController::class, 'show']);

Route::get('/jogcims', [JogcimController::class, 'index']);
Route::get('/jogcims/{jogcim}', [JogcimController::class, 'show']);

Route::get('/megyes', [MegyeController::class, 'index']);
Route::get('/megyes/{megye}', [MegyeController::class, 'show']);

Route::get('/cegcsoports', [CegcsoportController::class, 'index']);
Route::get('/cegcsoports/{cegcsoport}', [CegcsoportController::class, 'show']);

Route::get('/tamogatotts', [TamogatottController::class, 'index']);
Route::get('/tamogatotts/{tamogatott}', [TamogatottController::class, 'show']);

Route::get('/tamogatasosszeg', TamogatasOsszegController::class);
