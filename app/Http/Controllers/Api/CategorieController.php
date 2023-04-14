<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategorieResource;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

class CategorieController extends Controller
{
    #[OA\Get(
        path: "/api/categories",
        operationId: "index",
        description: "The list of names of categories",
        tags: ["Categories"],
        responses: [
            new OA\Response(
                response: 200,
                description: "The list of names of categories",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(properties: [
                        new OA\Property(property: "categories", type: "array"),
                    ], type: "object")
                )
            ),
        ]
    )]
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $categoriesNoms = Categorie::pluck('nom');
        $categoriesCollection = collect(['categories' => $categoriesNoms]);
        return CategorieResource::collection($categoriesCollection);
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
