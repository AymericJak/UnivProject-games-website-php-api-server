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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Gate::denies('store-commentaire')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vous n\'êtes pas autorisé à ajouter un like !'
            ], 403);
        }
        $like = new Like();
        $like->like = $request->like;
        $like->user_id = auth()->user()->id;
        $like->jeu_id = $request->jeu_id;
        $like->save();
        if ($like) {
            return response()->json([
                "status" => "success",
                'message' => "Like created successfully !",
                'like' => $like
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                'message' => "Erreur lors de la création du commentaire !",
            ], 422);
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $jeu_id)
    {
        try {
            $user_id = auth()->user()->id;
            $like = Like::where('jeu_id', $jeu_id)
                ->where('user_id', $user_id)
                ->firstOrFail();
            if (Gate::denies('delete-like', $like)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vous n\'êtes pas autorisé à supprimer ce like !'
                ], 403);
            }
            $like->delete();
            return response()->json([
                'status' => 'success',
                'message' => "Like successfully deleted",
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'comment not found!'], 422);
        }
    }
}
