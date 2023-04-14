<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
            'adherent' => $user,
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
            'adherent' => $user,
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
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function monProfil() {
        if (!Auth::check()) {
            return response()->json([
                "status" => "error",
                "message" => "Unauthorized"
            ]);
        }
        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            "message" => "Successfully profil info",
            'adherent' => $user,
            'commentaires' => "TODO",
            'achats' => "TODO",
            'likes' => "TODO"
        ]);
    }

}
