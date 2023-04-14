<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EditeurResource;
use App\Models\Editeur;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EditeurController extends Controller
{
    /**
     * Display a listing of the resource.
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
