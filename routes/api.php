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
    Route::post('login', 'login')->name('login');
    Route::post('register', 'register')->name('register');
    Route::post('logout', 'logout')->middleware(['auth:api'])->name('logout');
    Route::post('refresh', 'refresh')->name('refresh');
    Route::get('profil', 'profil')->name('profil-your');
    Route::get('profil/{user_id}', 'profil')->name('profil');
    Route::put('update/{user_id}', 'update')->name('update')->middleware(['auth']);
    Route::post('updateAvatar/{user_id}', 'updateAvatar')->name('updateAvatar')->middleware(['auth']);
});

Route::get('jeu', [\App\Http\Controllers\Api\JeuController::class, 'index']);

#Petit bonus
Route::get('jeu/FiltrageAgeMin', [\App\Http\Controllers\Api\JeuController::class, 'indexFiltrageAgeMin']);
Route::get('jeu/FiltrageDuree', [\App\Http\Controllers\Api\JeuController::class, 'indexFiltrageDuree']);
Route::get('jeu/FiltrageJoueursMin', [\App\Http\Controllers\Api\JeuController::class, 'indexFiltrageJoueursMin']);
Route::get('jeu/FiltrageJoueursMax', [\App\Http\Controllers\Api\JeuController::class, 'indexFiltrageJoueursMax']);
Route::get('jeu/FiltrageMostLiked', [\App\Http\Controllers\Api\JeuController::class, 'indexMostLiked']);
Route::get('jeu/FiltrageBestRated', [\App\Http\Controllers\Api\JeuController::class, 'indexBestRated']);

Route::patch('jeu/{id}/edit', [\App\Http\Controllers\Api\JeuController::class, 'edit'])->middleware(['auth','role:adherent-premium','role:administrateur']);;
Route::post('jeu/{id}/editUrl', [\App\Http\Controllers\Api\JeuController::class, 'edit_url'])->middleware(['auth','role:adherent-premium','role:administrateur']);;

Route::post('jeu/{id}/achat', [\App\Http\Controllers\Api\AchatController::class, 'create'])->middleware(['auth']);
Route::delete('jeu/{id}/achat', [\App\Http\Controllers\Api\AchatController::class, 'destroy'])->middleware(['auth']);

Route::post('jeu', [\App\Http\Controllers\Api\JeuController::class, 'store'])->middleware(['auth','role:adherent-premium','role:administrateur']);
Route::delete('jeu/{id}', [\App\Http\Controllers\Api\JeuController::class, 'destroy'])->middleware(['auth','role:adherent-premium','role:administrateur']);
Route::get('jeu/{id}', [\App\Http\Controllers\Api\JeuController::class, 'show'])->middleware(['auth','role:adherent']);

Route::get('commentaires', [\App\Http\Controllers\Api\CommentaireController::class, 'index'])->middleware(['auth']);
Route::post('jeu/{id}/commentaire', [\App\Http\Controllers\Api\CommentaireController::class,'store'])->middleware(['auth', 'role:adherent','role:adherent-premium','role:commentaire-moderateur','role:administrateur']);
Route::delete('commentaire/{id}', [\App\Http\Controllers\Api\CommentaireController::class,'destroy'])->middleware(['auth','role:administrateur','role:commentaire-moderateur']);
Route::patch('commentaire/{id}', [\App\Http\Controllers\Api\CommentaireController::class,'update'])->middleware(['auth','role:administrateur','role:commentaire-moderateur']);

Route::delete('/jeu/{id}/like', [\App\Http\Controllers\Api\LikeController::class,'destroy'])->middleware(['auth', 'role:adherent','role:adherent-premium','role:commentaire-moderateur','role:administrateur']);
Route::post('/jeu/{id}/like', [\App\Http\Controllers\Api\LikeController::class,'update'])->middleware(['auth', 'role:adherent','role:adherent-premium','role:commentaire-moderateur','role:administrateur']);

Route::get('/jeu/{id}/like/check', [\App\Http\Controllers\Api\LikeController::class, 'checkUserLike'])->middleware(['auth']);

Route::get('editeurs', [EditeurController::class, 'index']);

Route::get('themes', [ThemeController::class, 'index']);

Route::get('categories', [CategorieController::class, 'index']);
