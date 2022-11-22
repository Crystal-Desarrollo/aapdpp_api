<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
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
            "title" => ['string', 'required', 'max:50000'],
            "body" => ['string', 'required', 'max:50000'],
            "description" => ['string', 'nullable', 'max:50000'],
            "is_starred" => ['boolean'],
            "cover" => ['nullable', 'image', "max:5000"]
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
