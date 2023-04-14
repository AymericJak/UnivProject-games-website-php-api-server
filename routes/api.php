<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('jeu', [\App\Http\Controllers\Api\JeuController::class,'index']);

Route::get('jeu/FiltrageAgeMin', [\App\Http\Controllers\Api\JeuController::class,'indexFiltrageAgeMin']);
Route::get('jeu/FiltrageDuree', [\App\Http\Controllers\Api\JeuController::class,'indexFiltrageDuree']);
Route::get('jeu/FiltrageJoueursMin', [\App\Http\Controllers\Api\JeuController::class,'indexFiltrageJoueursMin']);
Route::get('jeu/FiltrageJoueursMax', [\App\Http\Controllers\Api\JeuController::class,'indexFiltrageJoueursMax']);
Route::get('jeu/FiltrageMostLiked', [\App\Http\Controllers\Api\JeuController::class,'indexMostLiked']);
Route::get('jeu/FiltrageBestRated', [\App\Http\Controllers\Api\JeuController::class,'indexBestRated']);

Route::post('jeu', [\App\Http\Controllers\Api\JeuController::class,'store']);
Route::patch('jeu/{id}', [\App\Http\Controllers\Api\JeuController::class,'edit']);


