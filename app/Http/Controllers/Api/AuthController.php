<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdherentRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;


class AuthController extends Controller {

    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    #[OA\Schema(
        schema: "UserResource",
        properties: [
            new OA\Property(property: "id", description: "identifiant de l'adhérent", type: "integer", format: "int54"),
            new OA\Property(property: "login", description: "login de l'adhérent", type: "string"),
            new OA\Property(property: "email", description: "email de l'adhérent", type: "string"),
            new OA\Property(property: "valide", description: "Statut de l'adhérent", type: "boolean"),
            new OA\Property(property: "nom", description: "nom de l'adhérent", type: "string"),
            new OA\Property(property: "prenom", description: "prenom de l'adhérent", type: "string"),
            new OA\Property(property: "pseudo", description: "pseudo de l'adhérent", type: "string"),
            new OA\Property(property: "avatar", description: "avatar de l'adhérent", type: "string"),

        ]
    )]
    #[OA\Post(
        path: "/api/login",
        operationId: "login",
        description: "Permet à un adhérent de se connecter",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(properties: [
                new OA\Property(property: "email", description: "Email de l'adhérent", type: "string", format: "email"),
                new OA\Property(property: "password", description: "Mot de passe de l'adhérent", type: "string", format: "password"),
            ]),
        ),
        tags: ["Utilisateur"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Adhérent connecté",
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: "status", type: "string"),
                    new OA\Property(property: "message", type: "string"),
                    new OA\Property(property: "adherent", ref: "#/components/schemas/UserResource")
                ], type: "object")
            ),
            new OA\Response(
                response: 401,
                description: "Erreur - Unauthorized",
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: "status", type: "string"),
                    new OA\Property(property: "message", type: "string"),
                ], type: "object")
            ),
        ]
    )]
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }
        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'message' => 'Adherent logged successfully',
            'adherent' => new UserResource($user),
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    #[OA\Post(
        path: "/api/register",
        operationId: "register",
        description: "Permet à un utilisateur quelconque de créer un compte",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(properties: [
                new OA\Property(property: "login", description: "login de l'adhérent", type: "string", format: "login"),
                new OA\Property(property: "email", description: "Email de l'adhérent", type: "string", format: "email"),
                new OA\Property(property: "password", description: "Mot de passe de l'adhérent", type: "string", format: "password"),
                new OA\Property(property: "nom", description: "Nom de l'adhérent", type: "string", format: "nom"),
                new OA\Property(property: "prenom", description: "Prénom de l'adhérent", type: "string", format: "prenom"),
                new OA\Property(property: "pseudo", description: "Pseudo de l'adhérent", type: "string", format: "pseudo"),
            ]),
        ),
        tags: ["Utilisateur"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Adhérent connecté",
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: "status", type: "string"),
                    new OA\Property(property: "message", type: "string"),
                    new OA\Property(property: "adherent", ref: "#/components/schemas/UserResource"),
                    new OA\Property(
                        property: "authorisation",
                        properties: [
                            new OA\Property(property: "token", type: "string"),
                            new OA\Property(property: "type", type: "string"),
                        ],
                        type: "object"
                    ),
                ], type: "object")
            ),
            new OA\Response(
                response: 401,
                description: "Erreur - Unauthorized",
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: "status", type: "string"),
                    new OA\Property(property: "errors", properties: [
                        new OA\Property(property: "id", type: "array", items: new OA\Items(type: "string"))
                    ], type: "object"),
                ], type: "object")
            ),
        ]
    )]
    public function register(Request $request) {
        $request->validate([
            'login' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'pseudo' => 'required|string|max:255',
        ]);

        $user = User::create([
            'login' => $request->login,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'pseudo' => $request->pseudo,
            'avatar' => 'avatarParDefaut.png'
        ]);
        Auth::login($user);
        $credentials = $user->only('email', 'password');
        $token = Auth::attempt($credentials); // boolean;

        if (!$token) {
            return response()->json([
                'status' => 'Error',
                'message' => 'TODO : AFFICHER LES ERREURS',
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Adherent created successfully',
            'adherent' => new UserResource($user),
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    #[OA\Post(
        path: "/api/logout",
        operationId: "logout",
        description: "Permet à un adhérent de se déconnecter",
        tags: ["Utilisateur"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Adhérent déconnecté",
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: "status", type: "string"),
                    new OA\Property(property: "message", type: "string"),
                ], type: "object")
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized",
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: "message", type: "string"),
                ], type: "object")
            ),
        ]
    )]
    public function logout() {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh() {
        return response()->json([
            'status' => 'success',
            'user' => new UserResource(Auth::user()),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    #[OA\Get( //TODO Paramètre id_user
        path: "/api/profil",
        operationId: "profil",
        description: "Permet de regarder son profil ou celui d'un autre (en tant qu'admin)",
        tags: ["Utilisateur"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Adhérent connecté",
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: "status", type: "string"),
                    new OA\Property(property: "message", type: "string"),
                    new OA\Property(property: "adherent", ref: "#/components/schemas/UserResource"),
                    new OA\Property(property: "commentaires", type: "string"), //TODO
                    new OA\Property(property: "achats", type: "string"), //TODO
                    new OA\Property(property: "likes", type: "string"), //TODO

                ], type: "object")
            ),
            new OA\Response(
                response: 403,
                description: "Erreur - Unauthorized",
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: "status", type: "string"),
                    new OA\Property(property: "message", type: "string"),
                ], type: "object")
            ),
        ]
    )]
    public function profil($user_id) {
        $user = User::findOrFail($user_id);
        if (!Auth::check() || (Auth::user()->id != $user_id && !Auth::user()->isAdmin())) {
            return response()->json([
                "status" => "error",
                "message" => "Unauthorized"
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            "message" => "Successfully profil info",
            'adherent' => new UserResource($user),
            'commentaires' => $user->commentaires,
            'achats' => $user->achats,
            'likes' => $user->likes
        ]);
    }

    #[OA\Put(
        path: "/api/update", //TODO avec parametre
        operationId: "update",
        description: "Permet à un adhérent de modifier son profil ou celui d'un autre en tant qu'admin",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(properties: [
                new OA\Property(property: "email", description: "Email de l'adhérent", type: "string", format: "email"),
                new OA\Property(property: "password", description: "Mot de passe de l'adhérent", type: "string", format: "password"),
            ]),
        ),
        tags: ["Utilisateur"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Adhérent connecté",
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: "status", type: "string"),
                    new OA\Property(property: "message", type: "string"),
                    new OA\Property(property: "adherent", ref: "#/components/schemas/UserResource")
                ], type: "object")
            ),
            new OA\Response(
                response: 401,
                description: "Erreur - Unauthorized",
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: "status", type: "string"),
                    new OA\Property(property: "message", type: "string"),
                ], type: "object")
            ),
        ]
    )]
    public function update(AdherentRequest $request, $user_id) {
        if (!Auth::user()->isAdmin() && Auth::user()->id != $user_id) {
            return response()->json([
                "status" => "error",
                "message" => "Unauthorized"
            ], 422);
        }

        $user = User::findOrFail($user_id);

        if ($request->has('password')) {
            $request->merge([
                'password' => Hash::make($request->input('password'))
            ]);
        }

        $user->update($request->all());
        return response()->json([
            'status' => "success",
            'message' => "Adherent updated successfully",
            'adherent' => new UserResource($user)
        ], 200);
    }

    public function updateAvatar(Request $request, $user_id) {
        if (!Auth::user()->isAdmin() && Auth::user()->id != $user_id) {
            return response()->json([
                "status" => "error",
                "message" => "Unauthorized"
            ], 422);
        }
        $request->validate([
            'avatar' => 'required|string',
        ]);
        $user = User::findOrFail($user_id);
        $user->update([
            "avatar" => $request->avatar,
        ]);
        return response()->json([
            'status' => "success",
            'message' => "Adherent avatar updated successfully",
            "avatar" => $request->avatar
        ], 200);
    }
}
