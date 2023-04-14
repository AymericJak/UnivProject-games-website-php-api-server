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
use OpenApi\Attributes as OA;

class JeuController extends Controller
{
    #[OA\Put(
        path: "/api/jeux",
        operationId: "index",
        description: "Get the list of games",
        tags: ["Games"],
        security: [
            [
                "bearerAuth" => []
            ]
        ],
        parameters: [
            new OA\Parameter(
                name: "age",
                in: "query",
                description: "Minimum age for the game",
                required: false,
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "duree",
                in: "query",
                description: "Minimum duration of a game",
                required: false,
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "nb_joueurs_min",
                in: "query",
                description: "Minimum number of players for the game",
                required: false,
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "nb_joueurs_max",
                in: "query",
                description: "Maximum number of players for the game",
                required: false,
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "sortby",
                in: "query",
                description: "Sort order for the list of games",
                required: false,
                schema: new OA\Schema(type: "string")
            ),
            new OA\Parameter(
                name: "categorie",
                in: "query",
                description: "Game category",
                required: false,
                schema: new OA\Schema(type: "string")
            ),
            new OA\Parameter(
                name: "theme",
                in: "query",
                description: "Game theme",
                required: false,
                schema: new OA\Schema(type: "string")
            ),
            new OA\Parameter(
                name: "editeur",
                in: "query",
                description: "Game editor",
                required: false,
                schema: new OA\Schema(type: "string")
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of games",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(properties: [
                        new OA\Property(property: "Jeux", type: "array"),
                    ], type: "object")
                )
            ),
            new OA\Response(
                response: 422,
                description: "Unauthorized",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "status", type: "string"),
                        new OA\Property(property: "message", type: "string")
                    ]
                )
            )
        ]
    )]
    public function index(Request $request)
    {
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
            return response()->json([
                'status' => true,
                'Jeux' => $jeux->pluck('nom')->toArray()
            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized'
        ], 422);
    }

    public function indexVisiteur(Request $request)
    {
        $jeux = Jeu::inRandomOrder()->take(5)->get();
        return response()->json([
            'status' => true,
            'Jeux' => $jeux->pluck('nom')->toArray()
        ], 200);
    }

    public function indexAdherent(Request $request)
    {
        $jeux = Jeu::where('valide', true)->get();
        return response()->json([
            'status' => true,
            'Jeux' => $jeux->pluck('nom')->toArray()
        ], 200);
    }

    public function indexFiltrageAgeMin(Request $request)
    {
        $jeux = Jeu::orderBy('age_min')->get();
        return $jeux;
    }

    public function indexFiltrageDuree(Request $request)
    {
        $jeux = Jeu::orderBy('duree_partie')->get();
        return $jeux;
    }

    public function indexFiltrageJoueursMin(Request $request)
    {
        $jeux = Jeu::orderBy('nombre_joueurs_min')->get();
        return $jeux;
    }

    public function indexFiltrageJoueursMax(Request $request)
    {
        $jeux = Jeu::orderBy('nombre_joueurs_max')->get();
        return new JeuResource($jeux);
    }

    public function indexMostLiked(Request $request)
    {
        $jeux = Jeu::where('valide', true)->get();
        foreach ($jeux as $jeu) {
            $jeu->nb_likes = count($jeu->likes()->get());
        }
        return $jeux->sortByDesc('nb_likes')->take(5);
    }

    public function indexBestRated(Request $request)
    {
        $jeux = Jeu::where('valide', true)->get();
        foreach ($jeux as $jeu) {
            $commentaires = $jeu->commentaires()->get();
            $total = 0;
            foreach ($commentaires as $commentaire) {
                $total += $commentaire->note;
            }
            $jeu->note = $total / count($commentaires);
        }
        return $jeux->sortByDesc('note')->take(5);
    }

    public function store(JeuRequest $request)
    {
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
                $jeu->url_media = isset($request->url_media) ? $request->url_media : "no-image.png";
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

    public function edit(JeuRequest $request)
    {
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
                $jeu->save();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Game created successfully',
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

    #[OA\Put(
        path: "/api/jeux/{id}/url_media",
        operationId: "edit_url",
        description: "Update the media URL of a game",
        tags: ["Games"],
        security: [
            [
                "bearerAuth" => []
            ]
        ],
        requestBody: new OA\RequestBody(
            description: "Game's media URL",
            required: true,
            content: [
                "application/json" => [
                    "schema" => [
                        '$ref' => '#/components/schemas/EditUrlRequest'
                    ]
                ]
            ]
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation",
                content: [
                    "application/json" => [
                        "schema" => [
                            '$ref' => '#/components/schemas/JeuResource'
                        ]
                    ]
                ]
            ),
            new OA\Response(
                response: 422,
                description: "Invalid input data",
                content: [
                    "application/json" => [
                        "schema" => [
                            '$ref' => '#/components/schemas/Error'
                        ]
                    ]
                ]
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized",
                content: [
                    "application/json" => [
                        "schema" => [
                            '$ref' => '#/components/schemas/Error'
                        ]
                    ]
                ]
            ),
            new OA\Response(
                response: 404,
                description: "Game not found",
                content: [
                    "application/json" => [
                        "schema" => [
                            '$ref' => '#/components/schemas/Error'
                        ]
                    ]
                ]
            )
        ]
    )]
    public function edit_url(Request $request, $id)
    {
        if (Auth::user()->roles()->pluck('nom')->contains('adherent-premium')) {
            $jeu = Jeu::find($id);

            if (!$jeu) {
                return response()->json(['status' => 'error', 'message' => 'Jeu introuvable.'], 422);
            }

            if (!isset($request->url_media)) {
                return response()->json(['status' => 'error', 'message' => 'Renseignez un lien.'], 422);
            }
            $jeu->url_media = $request->url_media;

            if ($jeu->save()) {
                return response()->json(['status' => 'success', 'message' => 'Game updated successfully', 'jeu' => new JeuResource($jeu)], 200);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Une erreur est survenue lors de la modification du jeu.'], 422);
            }
        }
        return $this->throwUnauthorized();
    }

    #[OA\Post(
        path: "/api/jeux/{id}/achat",
        operationId: "achat",
        description: "Create a new purchase",
        tags: ["Purchases"],
        security: [
            [
                "bearerAuth" => [],
            ],
        ],
        requestBody: new OA\RequestBody(
            required: true,
            description: "Purchase details",
            content: [
                "application/json" => [
                    "schema" => [
                        '$ref' => "#/components/schemas/AchatRequest",
                    ],
                ],
            ],
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Purchase created successfully",
                content: new OA\JsonContent(
                    schema: [
                        "type" => "object",
                        "properties" => [
                            "status" => [
                                "type" => "string",
                                "example" => "success",
                            ],
                            "message" => [
                                "type" => "string",
                                "example" => "Purchase created successfully",
                            ],
                            "achat" => [
                                '$ref' => "#/components/schemas/Achat",
                            ],
                            "adherant" => [
                                '$ref' => "#/components/schemas/User",
                            ],
                            "jeu" => [
                                '$ref' => "#/components/schemas/JeuResource",
                            ],
                        ],
                    ],
                ),
            ),
            new OA\Response(
                response: 422,
                description: "Unable to create purchase",
                content: new OA\JsonContent(
                    schema: [
                        "type" => "object",
                        "properties" => [
                            "status" => [
                                "type" => "string",
                                "example" => "error",
                            ],
                            "message" => [
                                "type" => "string",
                                "example" => "L'achat n'a pas pu être réalisé",
                            ],
                            "errors" => [
                                "type" => "object",
                            ],
                        ],
                    ],
                ),
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized",
            ),
        ],
    )]
    public function achat(AchatRequest $request, $id)
    {
        if (Auth::user()->roles()->pluck('nom')->contains('adherent-premium')) {

            try {
                $jeu = Jeu::findOrFail($id);
                $achat = new Achat();
                $achat->date_achat = date('Y-m-d');
                $achat->lieu_achat = $request->lieu_achat;
                $achat->prix = $request->prix;
                $achat->user_id = Auth::user()->id;
                $achat->jeu_id = $id;
                $achat->save();
                return response()->json([
                        'status' => 'success',
                        'message' => 'Purchase created successfully',
                        'achat' => $achat,
                        'adherant' => Auth::user(),
                        'jeu' => new JeuResource($jeu)]
                    , 200);
            } catch (Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'L\'achat n\'a pas pu être réalisé',
                    'errors' => $e,
                ], 422);
            }
        }
        return $this->throwUnauthorized();
    }

    #[OA\Delete(
        path: "/api/achats/{id}",
        operationId: "destroy",
        description: "Delete a purchase",
        tags: ["Purchases"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: OA\In::PATH,
                required: true,
                description: "ID of the purchase to delete",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Purchase successfully deleted",
                content: new OA\JsonContent(
                    example: [
                        'status' => 'success',
                        'message' => 'Achat successfully deleted'
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Invalid request",
                content: new OA\JsonContent(
                    example: [
                        'status' => 'error',
                        'message' => 'L\'achat n\'existe pas',
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized",
                content: new OA\JsonContent(
                    example: [
                        'status' => 'error',
                        'message' => 'Unauthorized',
                    ]
                )
            )
        ]
    )]
    public function destroy($id)
    {
        if (Auth::user()->roles()->pluck('nom')->contains('adherent-premium')) {

            $achat = Achat::find($id);
            if (!$achat) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'L\'achat n\'existe pas',
                ], 422);
            }
            if (Auth::user()->roles()->pluck('nom')->contains('adherent-premium') && Auth::user()->id == $achat->user_id) {

                $achat->delete();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Achat successfully deleted'
                ], 200);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 422);
        }
        return $this->throwUnauthorized();
    }

    #[OA\Get(
        path: "/api/jeux/{id}",
        operationId: "showJeu",
        description: "Get detailed information about a game",
        tags: ["Jeux"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: OA\Parameter::PATH,
                description: "ID of the game",
                required: true,
                schema: new OA\Schema(
                    type: "integer"
                )
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation",
                content: new OA\JsonContent(
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "status",
                                type: "string"
                            ),
                            new OA\Property(
                                property: "message",
                                type: "string"
                            ),
                            new OA\Property(
                                property: "achats",
                                type: "array",
                                items: new OA\Schema(
                                    ref: "#/components/schemas/Achat"
                                )
                            ),
                            new OA\Property(
                                property: "commentaires",
                                type: "array",
                                items: new OA\Schema(
                                    ref: "#/components/schemas/Commentaire"
                                )
                            ),
                            new OA\Property(
                                property: "jeu",
                                type: "object",
                                ref: "#/components/schemas/Jeu"
                            ),
                            new OA\Property(
                                property: "nb_likes",
                                type: "integer"
                            ),
                            new OA\Property(
                                property: "note_moyenne",
                                type: "integer"
                            ),
                        ]
                    )
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized",
                content: new OA\JsonContent(
                    schema: new OA\Schema(
                        ref: "#/components/schemas/Error"
                    )
                )
            ),
            new OA\Response(
                response: 422,
                description: "Game not found",
                content: new OA\JsonContent(
                    schema: new OA\Schema(
                        ref: "#/components/schemas/Error"
                    )
                )
            )
        ]
    )]
    public function show(Request $request, $id)
    {
        if (Auth::user()->roles()->pluck('nom')->contains('adherent-premium')) {

            $jeu = Jeu::find($id);
            if (!$jeu) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Le jeu n\'existe pas',
                ], 422);
            }
            $jeu = Jeu::findOrFail($id);

            $achats = $jeu->achats;

            $commentaires = $jeu->commentaires;

            $nbLikes = $jeu->likes->count();
            $noteMoyenne = count($jeu->likes()->get());

            return response()->
            json([
                'status' => 'success',
                'message' => 'Les informations de ce jeu',
                'achats' => $achats,
                'commentaires' => $commentaires,
                'jeu' => new JeuResource($jeu),
                'nb_likes' => $nbLikes,
                'note_moyenne' => $noteMoyenne
            ], 200);
        }
        return $this->throwUnauthorized();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function throwUnauthorized(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized'
        ], 422);
    }


}
