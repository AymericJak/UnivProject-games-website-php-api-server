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
            'age' => 'integer',
            'duree' => 'integer',
            'nb_joueurs_min' => 'integer',
            'nb_joueurs_max' => 'integer',
            'sort' => 'asc|desc',
            'categorie' => 'string',
            'theme' => 'string',
            'editeur' => 'string',
        ];
    }
}
