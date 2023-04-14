<?php

use App\Http\Controllers\Api\CategorieController;
use App\Http\Controllers\Api\EditeurController;
use App\Http\Controllers\Api\ThemeController;
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

Route::controller(\App\Http\Controllers\Api\AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::get('monProfil', 'monProfil')->name('profil');
});

Route::get('jeu', [\App\Http\Controllers\Api\JeuController::class, 'index']);
Route::get('jeu/FiltrageAgeMin', [\App\Http\Controllers\Api\JeuController::class, 'indexFiltrageAgeMin']);
Route::get('jeu/FiltrageDuree', [\App\Http\Controllers\Api\JeuController::class, 'indexFiltrageDuree']);
Route::get('jeu/FiltrageJoueursMin', [\App\Http\Controllers\Api\JeuController::class, 'indexFiltrageJoueursMin']);
Route::get('jeu/FiltrageJoueursMax', [\App\Http\Controllers\Api\JeuController::class, 'indexFiltrageJoueursMax']);
Route::get('jeu/FiltrageMostLiked', [\App\Http\Controllers\Api\JeuController::class, 'indexMostLiked']);
Route::get('jeu/FiltrageBestRated', [\App\Http\Controllers\Api\JeuController::class, 'indexBestRated']);
Route::post('jeu', [\App\Http\Controllers\Api\JeuController::class, 'store']);


Route::post('commentaire', [\App\Http\Controllers\Api\CommentaireController::class,'store'])->middleware(['auth', 'role:adherent','role:visiteur','role:adherent-premium','role:commentaire-moderateur','role:administrateur']);
Route::delete('/commentaires/{id}', [\App\Http\Controllers\Api\CommentaireController::class,'destroy'])->middleware(['auth','role:administrateur','role:commentaire-moderateur']);
Route::patch('/commentaires/{id}', [\App\Http\Controllers\Api\CommentaireController::class,'update'])->middleware(['auth','role:administrateur','role:commentaire-moderateur']);

Route::patch('jeu/{id}', [\App\Http\Controllers\Api\JeuController::class, 'edit']);
Route::post('jeu/{id}/achat', [\App\Http\Controllers\Api\JeuController::class, 'achat']);
Route::delete('jeu/{id}', [\App\Http\Controllers\Api\JeuController::class, 'destroy']);


Route::get('editeurs', [EditeurController::class, 'index']);

Route::get('themes', [ThemeController::class, 'index']);

Route::get('categories', [CategorieController::class, 'index']);