<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name' => 'string|required',
            'email' => 'string|unique:users,email,' . $this->user->id,
            'phone' => 'string|nullable|max:15',
            'address' => 'string|nullable|max:100',
            'additional_info' => 'string|nullable|max:50',
            'is_admin' => 'required|boolean'
        ];
    }
}
