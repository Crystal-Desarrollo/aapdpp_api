<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
     * Prepare inputs for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'is_admin' => boolval($this->is_admin),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "email" => ['required', 'string', 'unique:users,email'],
            "name" => ['required', 'string'],
            'phone' => 'string|nullable|max:15',
            'address' => 'string|nullable|max:100',
            'additional_info' => 'string|nullable|max:512',
            'picture' => 'nullable|image|max:10000',
            'is_admin' => 'nullable|boolean'
        ];
    }
}
