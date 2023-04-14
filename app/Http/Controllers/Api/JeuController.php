<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jeu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JeuController extends Controller
{
    public function index(request $request){
        if (Auth::check()) {
            return $this->indexAdherent($request);
        }
        else{
            return $this->indexVisiteur($request);
        }
    }

    public function indexVisiteur(Request $request){
        $jeux = Jeu::inRandomOrder()->take(5)->get();
        return $jeux;
    }

    public function indexAdherent(Request $request){
        $jeux = Jeu::where('valide', true)->get();
        return $jeux;
    }

    public function indexFiltrageAgeMin(Request $request){
        $jeux = Jeu::orderBy('age_min')->get();
        return $jeux;
    }

    public function indexFiltrageDuree(Request $request){
        $jeux = Jeu::orderBy('duree')->get();
        return $jeux;
    }

    public function indexFiltrageJoueursMin(Request $request){
        $jeux = Jeu::orderBy('nombre_joueurs_min')->get();
        return $jeux;
    }

    public function indexFiltrageJoueursMax(Request $request){
        $jeux = Jeu::orderBy('nombre_joueurs_max')->get();
        return $jeux;
    }
}
