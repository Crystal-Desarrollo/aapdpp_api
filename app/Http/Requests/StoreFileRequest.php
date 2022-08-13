<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFileRequest extends FormRequest
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
            'folder' => 'required|exists:folders,id',
            'files' => 'array|nullable|sometimes',
            'files.*' => 'file|max:5000|mimes:xlsx,pdf,doc,docx,jpg,png,jpeg,webp,gif,txt,csv'
        ];
    }
}
