<?php

namespace App\Http\Resources;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\File;

class JeuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        try {
            $filePath = storage_path("app/images/oeuvres/{$this->url_media}");
            $image_encoded = base64_encode(File::get($filePath));
        } catch (Exception $e) {
            $filePath = storage_path("app/images/oeuvres/no-image.png");
            $image_encoded = base64_encode(File::get($filePath));
        }

        $image_encoded = "data:image/png;base64," . $image_encoded;

        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'description' => $this->description,
            'langue' => $this->langue,
            'url_media' => $image_encoded,
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
