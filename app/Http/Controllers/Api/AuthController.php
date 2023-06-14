<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdherentRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {

    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

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
            'valide' => false,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'pseudo' => $request->pseudo,
            'avatar' => 'avatarParDefaut.png'
        ]);

        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
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
            ]
        ]);
    }

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

    public function profil($user_id = 0) {
        if ($user_id === 0)
            $user_id = Auth::user()->id;
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
