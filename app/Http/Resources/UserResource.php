<?php

namespace App\Http\Resources;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\File;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        try {
            $filePath = storage_path("app/images/oeuvres/{$this->avatar}");
            $image_encoded = base64_encode(File::get($filePath));
        } catch (Exception $e) {
            $filePath = storage_path("app/images/oeuvres/no-image.png");
            $image_encoded = base64_encode(File::get($filePath));
        }

        return [
            'id' => $this->id,
            'login' => $this->login,
            'email' => $this->email,
            'valide' => $this->valide,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'pseudo' => $this->pseudo,
            'avatar' => $image_encoded,
        ];
    }
}
