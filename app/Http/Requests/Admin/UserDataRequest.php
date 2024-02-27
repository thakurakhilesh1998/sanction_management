<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;


class UserDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string>, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $rules= [
            'username'=>['required','string'],
            'email'=>['required','email'],
            'password' => ['required','string','min:8','confirmed'],
            'role'=>['required','string'],
            'district'=>['nullable','string'],
            'block_name'=>['nullable','string'],
            'gp_name'=>['nullable','string'],
        ];
        return $rules;
    }
}
