<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JeuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'nom' => 'required|string',
            'description' => 'required|string',
            'langue' => 'required|string',
            'age_min' => 'required|integer',
            'nombre_joueurs_min' => 'required|integer',
            'nombre_joueurs_max' => 'required|integer',
            'duree_partie' => 'required|integer',
            'categorie' => 'required|string',
            'theme' => 'required|string',
            'editeur' => 'required|string',
            'url_media' => 'string',
        ];
    }
}
