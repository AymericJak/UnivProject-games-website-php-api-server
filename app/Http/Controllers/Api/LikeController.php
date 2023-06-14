<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function update(Request $request, string $id)
    {
        if (Gate::denies('update-like')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vous n\'êtes pas autorisé à ajouter un like !'
            ], 403);
        }

        $user_id = auth()->user()->id;
        $like = Like::where('jeu_id', $id)
            ->where('user_id', $user_id)
            ->first();

        if ($like) {
            if (Gate::denies('delete-like', $like)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vous n\'êtes pas autorisé à supprimer ce like !'
                ], 403);
            }
            $like->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Like supprimé avec succès !',
            ], 200);
        } else {
            $like = new Like();
            $like->like = $request->like;
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
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }


    public function destroy(string $jeu_id)
    {

    }
}
