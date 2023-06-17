<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AchatRequest;
use App\Http\Requests\JeuRequest;
use App\Http\Resources\JeuResource;
use App\Models\Achat;
use App\Models\Categorie;
use App\Models\Editeur;
use App\Models\Jeu;
use App\Models\Theme;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class JeuController extends Controller {

    public function index(Request $request) {
        if (!Auth::check()) {
            return $this->indexVisiteur($request);
        } elseif (Auth::user()->roles()->pluck('nom')->contains('adherent')) {
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
            $listeJeux = [];
            foreach ($jeux as $jeu) {
                $tmp_jeu = new JeuResource($jeu);

                $filePath = storage_path("app/images/oeuvres/$jeu->url_media");
                $image_encoded = base64_encode(File::get($filePath));

                $tmp_jeu->url_media = $image_encoded;
                $listeJeux[] = $tmp_jeu;
            }
            return response()->json([
                'status' => true,
                'jeux' => $listeJeux,
                ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized'
        ], 422);
    }

    public function indexVisiteur(Request $request) {
        $jeux = Jeu::inRandomOrder()->take(5)->get();
        $listeJeux = [];
        foreach ($jeux as $jeu) {
            $listeJeux[] = new JeuResource($jeu);
        }

        return response()->json([
            'status' => true,
            'jeux' => $listeJeux
        ], 200);
    }

    public function indexAdherent(Request $request) {
        $jeux = Jeu::where('valide', true)->get();
        $listeJeux = [];
        foreach ($jeux as $jeu) {
            $listeJeux[] = new JeuResource($jeu);
        }

        return response()->json([
            'status' => true,
            'jeux' => $listeJeux
        ], 200);
    }

    public function indexFiltrageAgeMin(Request $request) {
        if (!Auth::check()) {
            return $this->indexVisiteur($request);
        } elseif (Auth::user()->roles()->pluck('nom')->contains('adherent')) {
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
            $jeux = Jeu::orderBy('age_min')->get();
            $listeJeux = [];
            foreach ($jeux as $jeu) {
                $listeJeux[] = new JeuResource($jeu);
            }
            return response()->json([
                'status' => true,
                'jeux' => $listeJeux
            ], 200);
        }
    }

    public function indexFiltrageDuree(Request $request) {
        if (!Auth::check()) {
            return $this->indexVisiteur($request);
        } elseif (Auth::user()->roles()->pluck('nom')->contains('adherent')) {
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
            $jeux = Jeu::orderBy('duree_partie')->get();
            $listeJeux = [];
            foreach ($jeux as $jeu) {
                $listeJeux[] = new JeuResource($jeu);
            }

            return response()->json([
                'status' => true,
                'jeux' => $listeJeux
            ], 200);
        }
    }

    public function indexFiltrageJoueursMin(Request $request) {
        if (!Auth::check()) {
            return $this->indexVisiteur($request);
        } elseif (Auth::user()->roles()->pluck('nom')->contains('adherent')) {
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
            $jeux = Jeu::orderBy('nombre_joueurs_min')->get();
            $listeJeux = [];
            foreach ($jeux as $jeu) {
                $listeJeux[] = new JeuResource($jeu);
            }

            return response()->json([
                'status' => true,
                'jeux' => $listeJeux
            ], 200);
        }
    }

    public function indexFiltrageJoueursMax(Request $request) {
        if (!Auth::check()) {
            return $this->indexVisiteur($request);
        } elseif (Auth::user()->roles()->pluck('nom')->contains('adherent')) {
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
            $jeux = Jeu::orderBy('nombre_joueurs_max')->get();
            $listeJeux = [];
            foreach ($jeux as $jeu) {
                $listeJeux[] = new JeuResource($jeu);
            }

            return response()->json([
                'status' => true,
                'jeux' => $listeJeux
            ], 200);
        }
    }

    public function indexMostLiked(Request $request) {
        if (!Auth::check()) {
            return $this->indexVisiteur($request);
        } elseif (Auth::user()->roles()->pluck('nom')->contains('adherent')) {
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
            $listeJeux = [];
            foreach ($jeux as $jeu) {
                $jeu->nb_likes = count($jeu->likes()->get());
            }
            $jeux = $jeux->sortByDesc('nb_likes');

            foreach ($jeux as $jeu) {
                $listeJeux[] = new JeuResource($jeu);
            }

            return response()->json([
                'status' => true,
                'jeux' => $listeJeux
            ], 200);
        }

    }

    public function indexBestRated(Request $request) {
        if (!Auth::check()) {
            return $this->indexVisiteur($request);
        } elseif (Auth::user()->roles()->pluck('nom')->contains('adherent')) {
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
            $listeJeux = [];
            foreach ($jeux as $jeu) {
                $commentaires = $jeu->commentaires()->get();
                $total = 0;
                foreach ($commentaires as $commentaire) {
                    $total += $commentaire->note;
                }
                if($total>0){
                    $jeu->note = $total / count($commentaires);
                } else{
                    $jeu->note = 0;
                }
            }
            $jeux= $jeux->sortByDesc('note');

            foreach ($jeux as $jeu) {
                $listeJeux[] = new JeuResource($jeu);
            }

            return response()->json([
                'status' => true,
                'jeux' => $listeJeux
            ], 200);
        }
    }

    public function store(JeuRequest $request) {
        if (Auth::user()->roles()->pluck('nom')->contains('adherent-premium')) {
            try {
                $jeu = new Jeu();
                $jeu->nom = $request->nom;
                $jeu->description = $request->description;
                $jeu->langue = $request->langue;
                $jeu->age_min = $request->age_min;
                $jeu->nombre_joueurs_min = $request->nombre_joueurs_min;
                $jeu->nombre_joueurs_max = $request->nombre_joueurs_max;
                $jeu->duree_partie = $request->duree_partie;
                $jeu->categorie_id = Categorie::where('nom', $request->categorie)->value('id');
                $jeu->theme_id = Theme::where('nom', $request->theme)->value('id');
                $jeu->editeur_id = Editeur::where('nom', $request->editeur)->value('id');
                $jeu->valide = true;
                $jeu->url_media = "no-image.png";
                $jeu->save();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Game created successfully',
                    'jeu' => new JeuResource($jeu),
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Le jeu n\'a pas pu être créé',
                    'errors' => $e,
                ], 422);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 422);
        }
    }

    public function edit(JeuRequest $request) {
        if (Auth::user()->roles()->pluck('nom')->contains('adherent-premium')) {
            try {
                $jeu = Jeu::find($request->id);
                $jeu->nom = $request->nom;
                $jeu->description = $request->description;
                $jeu->langue = $request->langue;
                $jeu->age_min = $request->age_min;
                $jeu->nombre_joueurs_min = $request->nombre_joueurs_min;
                $jeu->nombre_joueurs_max = $request->nombre_joueurs_max;
                $jeu->duree_partie = $request->duree_partie;
                $jeu->categorie_id = Categorie::where('nom', $request->categorie)->value('id');
                $jeu->theme_id = Theme::where('nom', $request->theme)->value('id');
                $jeu->editeur_id = Editeur::where('nom', $request->editeur)->value('id');
                $jeu->save();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Game updated successfully',
                    'jeu' => new JeuResource($jeu),
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Le jeu n\'a pas pu être modifié',
                    'errors' => $e,
                ], 422);
            }
        } else {
            return $this->throwUnauthorized();
        }
    }

    public function edit_url(Request $request, $id) {
        if (Auth::user()->roles()->pluck('nom')->contains('adherent-premium')) {
            $jeu = Jeu::find($id);

            if (!$jeu) {
                return response()->json(['status' => 'error', 'message' => 'Jeu introuvable.'], 422);
            }
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $file = $request->file('image');
            } else {
                return response()->json(['status' => 'error', 'message' => 'Aucun fichier.'], 422);
            }


            $nom = $request->url_media;
            $now = time();
            $nom = sprintf("%s_%d.%s", $nom, $now, $file->extension());
            $file->storeAs('images/oeuvres/', $nom);

            $jeu->url_media = $nom;

            if ($jeu->save()) {
                return response()->json(['status' => 'success', 'message' => 'Game updated successfully', 'jeu' => new JeuResource($jeu)], 200);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Une erreur est survenue lors de la modification du jeu.'], 422);
            }
        }
        return $this->throwUnauthorized();
    }

    public function show(Request $request, $id) {
        if (Auth::user()->roles()->pluck('nom')->contains('adherent')) {

            $jeu = Jeu::find($id);
            if (!$jeu) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Le jeu n\'existe pas',
                ], 422);
            }
            $jeu = Jeu::findOrFail($id);

            try{
                $filePath = storage_path("app/images/oeuvres/$jeu->url_media");
                $image_encoded = base64_encode(File::get($filePath));
            }catch (Exception $e){
                $filePath = storage_path("app/images/oeuvres/no-image.png");
                $image_encoded = base64_encode(File::get($filePath));
            }

            $achats = $jeu->achats;

            $commentaires = $jeu->commentaires;

            if ($jeu->likes && $jeu->likes->count()>= 1) {
                $nbLikes = $jeu->likes->count();
                $noteMoyenne = count($jeu->likes()->get()) / $nbLikes;
                $noteMoyenne = round($jeu->commentaires()->average('note'), 2);
                $prixMoyen = round($jeu->achats()->average('prix'), 2);
            } else {
                $nbLikes = 0;
                $noteMoyenne = 0;
                $prixMoyen = 0;
            }



            return response()->
            json([
                'status' => 'success',
                'message' => 'Les informations de ce jeu',
                'achats' => $achats,
                'commentaires' => $commentaires,
                'jeu' => new JeuResource($jeu),
                'likes' => $jeu->likes,
                'nb_likes' => $nbLikes,
                'note_moyenne' => $noteMoyenne,
                'image_enc' => $image_encoded,
                'prix_moyen' => $prixMoyen
            ], 200);
        }
        return $this->throwUnauthorized();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function throwUnauthorized(): \Illuminate\Http\JsonResponse {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized'
        ], 422);
    }


}
