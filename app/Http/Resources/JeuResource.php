<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JeuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'nom' => $this->nom,
            'description' => $this->description,
            'langue' => $this->langue,
            'url_media' => $this->url_media,
            'age_min' => $this->age_min,
            'nombre_joueurs_min' => $this->nombre_joueurs_min,
            'nombre_joueurs_max' => $this->nombre_joueurs_max,
            'duree_partie' => $this->duree_partie,
            'valide' => $this->valide,
            'categorie' => new CategorieResource($this->categorie),
            'theme' => new ThemeResource($this->theme),
            'editeur' => new EditeurResource($this->editeur),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
