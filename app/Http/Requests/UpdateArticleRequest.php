<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "title" => ['string', 'required'],
            "body" => ['string', 'required'],
            "description" => ['string', 'nullable'],
            "is_starred" => ['boolean'],
            "cover" => ['nullable', 'sometimes', 'image', "max:5000"]
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'título',
            'body' => 'noticia',
            'description' => 'descripción',
            'cover' => 'imagen de portada',
        ];
    }
}
