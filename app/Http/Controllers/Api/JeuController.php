<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jeu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JeuController extends Controller
{
    public function index(request $request){
    }

    public function indexVisiteur(Request $request){
        $jeux = Jeu::inRandomOrder()->take(5)->get();
        return $jeux;
    }

    public function indexAdherent(Request $request){
        if (Auth::check()) {
            $jeux = Jeu::where('valide', true)->get();
            return $jeux;
        }else {
            return null;
        }
    }
}
