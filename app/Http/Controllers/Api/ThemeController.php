<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ThemeResource;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

class ThemeController extends Controller
{
    #[OA\Get(
        path: "/api/themes",
        operationId: "index",
        description: "The list of theme's names",
        tags: ["Themes"],
        responses: [
            new OA\Response(
                response: 200,
                description: "The list of theme's names",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(properties: [
                        new OA\Property(property: "themes", type: "array"),
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
        $themesNoms = Theme::pluck('nom');
        $themesCollection = collect(['theme' => $themesNoms]);
        return ThemeResource::collection($themesCollection);
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
