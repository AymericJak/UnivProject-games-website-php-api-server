<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentaireRequest;
use App\Http\Resources\CommentaireResource;
use App\Models\Commentaire;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

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

    #[Post(
        path: '/api/commentaire',
        operationId: 'create',
        description: 'Ajouter un commentaire dans la base de données',
        requestBody: new RequestBody(
            required: true,
        ),
        tags: ['Commentaires'],
        responses: [
            new Response(
                response: 200,
                description: "Création d'un commentaire",
                content: new JsonContent(properties: [
                    new Property(property: 'status', type: 'boolean'),
                    new Property(property: 'message', type: 'string'),
                    new Property(property: 'commentaire'),
                ], type: 'object')
            ),
            new Response(
                response: 422,
                description: 'Erreur',
                content: new JsonContent(properties: [
                    new Property(property: 'message', type: 'string'),
                    new Property(
                        property: 'errors',
                        properties: [
                            new Property(property: 'commentaire|date_com|note|etat', type: 'array', items: new Items(type: 'string')),
                        ],
                        type: 'object',
                    ),
                ], type: 'object')
            ),
        ]
    )]
    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentaireRequest $request, string $id)
    {
        if (Gate::denies('store-commentaire')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vous n\'êtes pas autorisé à ajouter un commentaire !',
            ], 403);
        }
        $commentaire = new Commentaire;
        $commentaire->commentaire = $request->commentaire;
        $commentaire->date_com = $request->date_com;
        $commentaire->note = $request->note;
        if ($request->etat) {
            $commentaire->etat = $request->etat;
        } else {
            $commentaire->etat = 'public';
        }
        $commentaire->user_id = auth()->user()->id;
        $commentaire->jeu_id = $id;
        $commentaire->save();
        if ($commentaire) {
            return response()->json([
                'status' => 'success',
                'message' => 'Commentaire created successfully !',
                'commentaire' => $commentaire,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la création du commentaire !',
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

    #[Put(
        path: '/commentaires/{id}',
        operationId: 'update',
        description: 'Modifier un commentaire dans la base',
        requestBody: new RequestBody(
            required: true,
        ), tags: ['Commentaires'],
        parameters: [
            new Parameter(
                name: 'id',
                description: 'Identifiant du commentaire',
                in: 'path', required: 'true',
                schema: new Schema(type: 'integer')),
        ],
        responses: [
            new Response(
                response: 200,
                description: "Modification d'un commentaire",
                content: new JsonContent(properties: [
                    new Property(property: 'status', type: 'boolean'),
                    new Property(property: 'message', type: 'string'),
                    new Property(property: 'commentaire'),
                ], type: 'object')
            ),
        ],
    )]
    /**
     * Update the specified resource in storage.
     */
    public function update(CommentaireRequest $request, int $id)
    {
        $commentaire = Commentaire::findOrFail($id);
        if (Gate::denies('update-commentaire', $commentaire)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vous n\'êtes pas autorisé à modifier ce commentaire !',
            ], 403);
        }
        $commentaire->update($request->all());

        $commentaire->commentaire = $request->commentaire;
        $commentaire->date_com = new dateTime;
        $commentaire->note = $request->note;
        if ($request->etat) {
            $commentaire->etat = $request->etat;
        } else {
            $commentaire->etat = 'public';
        }
        $commentaire->user_id = auth()->user()->id;
        $commentaire->jeu_id = $request->jeu_id;
        $commentaire->save();

        if ($commentaire) {
            return response()->json([
                'status' => 'success',
                'message' => 'Commentaire updated successfully !',
                'commentaire' => $commentaire,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la modification du commentaire !',
            ], 422);
        }

    }

    #[Delete(
        path: '/commentaires/{id}',
        operationId: 'destroy',
        description: 'Supprime un commentaire',
        tags: ['Commentaires'],
        parameters: [
            new Parameter(
                name: 'id',
                description: 'Identifiant du commentaire',
                in: 'path', required: 'true',
                schema: new Schema(type: 'integer')),
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Supprime un commentaire',
                content: new JsonContent(properties: [
                    new Property(property: 'status', type: 'boolean'),
                    new Property(property: 'message', type: 'string'),
                ], type: 'object')
            ),
            new Response(
                response: 404,
                description: 'Commentaire non trouvée',
                content: new JsonContent(properties: [
                    new Property(property: 'message', type: 'string'),
                    new Property(property: 'errors', properties: [
                        new Property(property: 'id', type: 'array', items: new Items(type: 'string')),
                    ], type: 'object'
                    ),
                ], type: 'object')
            ),
        ]
    )]
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $commentaire = Commentaire::findOrFail($id);
            if (Gate::denies('delete-commentaire', $commentaire)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vous n\'êtes pas autorisé à supprimer ce commentaire !',
                ], 403);
            }
            $commentaire->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Comment successfully deleted',
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'comment not found!'], 422);
        }
    }
}
