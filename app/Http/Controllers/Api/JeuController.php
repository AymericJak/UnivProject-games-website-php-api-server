<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\JeuRequest;
use App\Models\Jeu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JeuController extends Controller
{
    public function index(JeuRequest $request){
        if (Auth::check()) {
            return $this->indexVisiteur($request);
        }
        $age = $request->query('age');
        $duree = $request->query('duree');
        $nb_joueurs_min = $request->query('nb_joueurs_min');
        $nb_joueurs_max = $request->query('nb_joueurs_max');
        $sort = $request->query('sortby');
        $categorie = $request->query('categorie');
        $theme = $request->query('theme');
        $editeur = $request->query('editeur');

        $query = Jeu::query();

        if ($age) {
            $query->where('age_min', '>=', $age);
        }

        if ($duree) {
            $query->where('duree_min', '>=', $duree);
        }

        if ($nb_joueurs_min) {
            $query->where('nb_joueurs_min', '>=', $nb_joueurs_min);
        }

        if ($nb_joueurs_max) {
            $query->where('nb_joueurs_max', '<=', $nb_joueurs_max);
        }

        if ($categorie) {
            $query->where('categorie', '=', $categorie);
        }

        if ($theme) {
            $query->where('theme', '=', $theme);
        }

        if ($editeur) {
            $query->where('editeur', '=', $editeur);
        }

        if ($sort && in_array($sort, ['asc', 'desc'])) {
            $query->orderBy('nom', $sort);
        }

        $jeux = $query->get();
        return response()->json([
            'status' => true,
            'Jeux' => $jeux->pluck('nom')->toArray()
        ], 200);
    }

    public function indexVisiteur(Request $request){
        $jeux = Jeu::inRandomOrder()->take(5)->get();
        return response()->json([
            'status' => true,
            'Jeux' => $jeux->pluck('nom')->toArray()
            ], 200);
    }

    public function indexAdherent(Request $request){
        $jeux = Jeu::where('valide', true)->get();
        return response()->json([
            'status' => true,
            'Jeux' => $jeux->pluck('nom')->toArray()
        ], 200);
    }

    public function indexFiltrageAgeMin(Request $request){
        $jeux = Jeu::orderBy('age_min')->get();
        return $jeux;
    }

    public function indexFiltrageDuree(Request $request){
        $jeux = Jeu::orderBy('duree_partie')->get();
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

    public function indexMostLiked(Request $request){
        $jeux = Jeu::where('valide', true)->get();
        foreach($jeux as $jeu){
            $jeu->nb_likes = count($jeu->likes()->get());
        }
        return $jeux->sortByDesc('nb_likes')->take(5);
    }

    public function indexBestRated(Request $request){
        $jeux = Jeu::where('valide', true)->get();
        foreach($jeux as $jeu){
            $commentaires = $jeu->commentaires()->get();
            $total = 0;
            foreach ($commentaires as $commentaire){
                $total += $commentaire->note;
            }
            $jeu->note = $total / count($commentaires);
        }
        return $jeux->sortByDesc('note')->take(5);
    }
}
