<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdherentRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array {
        return [
            "login" => "required|string|between:1,50|unique:users",
            "email" => "required|string|between:1,50|unique:users",
            "password" => "required|string|between:1,50",
            "nom" => "required|string|between:1,50",
            "prenom" => "required|string|between:1,50",
            "pseudo" => "required|string|between:1,50",
        ];
    }
}
