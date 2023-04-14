<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentaireRequest;
use App\Http\Resources\CommentaireResource;
use App\Models\Commentaire;
use Illuminate\Http\Request;

class CommentaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commentaires = Commentaire::all();
        return CommentaireResource::collection($commentaires);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentaireRequest $request)
    {
        $commentaire = new Commentaire();
        $commentaire->commentaire = $request->commentaire;
        $commentaire->date_com = $request->date_com;
        $commentaire->note = $request->note;
        if($request->etat){
            $commentaire->etat = $request->etat;
        } else{
            $commentaire->etat = 'public';
        }
        $commentaire->user_id = auth()->user()->id;
        $commentaire->jeu_id = $request->jeu_id;
        $commentaire->save();
        if($commentaire) {
            return response()->json([
                "status" => "success",
                'message' => "Commentaire created successfully !",
                'commentaire' => $commentaire
            ], 200);
        }else{
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
