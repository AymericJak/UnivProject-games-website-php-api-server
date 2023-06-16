<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id) {
        $like = new Like();
        $like->user_id = auth()->user()->id;
        $like->jeu_id = $id;
        $like->save();

        if ($like) {
            return response()->json([
                'status' => 'success',
                'message' => 'Like créé avec succès !',
                'like' => $like
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la création du like !',
            ], 422);
        }
    }

    public function update(Request $request, string $id) {
        $user_id = auth()->user()->id;
        $like = Like::where('jeu_id', $id)
            ->where('user_id', $user_id)
            ->first();

        if ($like) {
            return $this->destroy($user_id, $id);
        } else {
            return $this->create($id);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        //
    }


    public function destroy($user_id, $id) {
        Like::where('user_id', $user_id)
            ->where('jeu_id', $id)
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Like supprimé avec succès !',
        ], 200);
    }

    public function checkUserLike(int $gameId) {
        $user = auth()->user();
        $isLiked = $user->likes()->where('jeu_id', $gameId)->exists();
        return response()->json([
            'status' => 'success',
            'is_liked' => $isLiked
        ]);
    }
}
