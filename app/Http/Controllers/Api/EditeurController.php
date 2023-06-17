<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EditeurResource;
use App\Models\Editeur;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;

class EditeurController extends Controller
{
    #[Get(
        path: '/api/editeurs',
        operationId: 'index',
        description: "The list of editor's names",
        tags: ['Editors'],
        responses: [
            new Response(
                response: 200,
                description: "The list of editor's names",
                content: new JsonContent(
                    type: 'array',
                    items: new Items(properties: [
                        new Property(property: 'editeurs', type: 'array'),
                    ], type: 'object')
                )
            ),
        ]
    )]
    /**
     * Display a listing of the resource.
     *
     * @throws Exception
     */
    public function index(): AnonymousResourceCollection
    {
        $editeursNoms = Editeur::pluck('nom');
        $editeursCollection = collect(['editeurs' => $editeursNoms]);

        return EditeurResource::collection($editeursCollection);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
