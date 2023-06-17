<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdherentRequest;
use App\Http\Resources\UserResource;
use App\Models\Jeu;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = auth()->attempt($credentials);
        if (! $token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }
        $user = auth()->user();

        return response()->json([
            'status' => 'success',
            'message' => 'Adherent logged successfully',
            'adherent' => new UserResource($user),
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ],
        ]);
    }

    public function register(Request $request): JsonResponse
    {
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
            'valide' => true,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'pseudo' => $request->pseudo,
            'avatar' => 'no-image.png',
        ]);

        $credentials = $request->only('email', 'password');

        $token = auth()->attempt($credentials);
        if (! $token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Adherent created successfully',
            'adherent' => new UserResource($user),
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ],
        ]);
    }

    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'user' => new UserResource(auth()->user()),
            'authorisation' => [
                'token' => auth()->refresh(),
                'type' => 'bearer',
            ],
        ]);
    }

    public function profil(int $user_id = 0): JsonResponse
    {
        if ($user_id === 0) {
            $user_id = auth()->user()->id;
        }
        $user = User::findOrFail($user_id);

        try {
            $filePath = storage_path("app/images/oeuvres/{$user->avatar}");
            $image_encoded = base64_encode(File::get($filePath));
        } catch (Exception $e) {
            $filePath = storage_path('app/images/oeuvres/no-image.png');
            $image_encoded = base64_encode(File::get($filePath));
        }
        if (! auth()->check() || (auth()->user()->id != $user_id && ! auth()->user()->isAdmin())) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }
        $achats = [];
        foreach ($user->achats as $achat) {
            $jeu = Jeu::findOrFail($achat->jeu_id);
            $achats[] = [
                'user_id' => $achat->user_id,
                'jeu_id' => $achat->jeu_id,
                'jeu_nom' => $jeu->nom,
                'date_achat' => $achat->date_achat,
                'lieu_achat' => $achat->lieu_achat,
                'prix' => $achat->prix,
                'created_at' => $achat->created_at,
                'updated_at' => $achat->updated_at,
            ];
        }
        $likes = [];
        foreach ($user->likes as $like) {
            $jeu = Jeu::findOrFail($like->jeu_id);
            $likes[] = [
                'jeu_id' => $like->jeu_id,
                'jeu_nom' => $jeu->nom,
            ];
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully profil info',
            'adherent' => new UserResource($user),
            'commentaires' => $user->commentaires,
            'achats' => $achats,
            'likes' => $likes,
        ]);
    }

    public function update(AdherentRequest $request, $user_id): JsonResponse
    {
        if (! auth()->user()->isAdmin() && auth()->user()->id != $user_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 422);
        }

        $user = User::findOrFail($user_id);

        if ($request->has('password')) {
            $request->merge([
                'password' => Hash::make($request->input('password')),
            ]);
        }

        $user->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Adherent updated successfully',
            'adherent' => new UserResource($user),
        ], 200);
    }

    public function updateAvatar(Request $request, $user_id): JsonResponse
    {
        if (! auth()->user()->isAdmin() && auth()->user()->id != $user_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 422);
        }

        $user = User::findOrFail($user_id);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
        } else {
            return response()->json(['status' => 'error', 'message' => 'Aucun fichier.'], 422);
        }

        $nom = $request->url_media;
        $now = time();
        $nom = sprintf('%s_%d.%s', $nom, $now, $file->extension());
        $file->storeAs('images/oeuvres/', $nom);

        $user->avatar = $nom;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Adherent avatar updated successfully',
        ], 200);
    }
}
