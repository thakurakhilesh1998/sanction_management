<?php

namespace App\Http\Requests\PGharStatus;

use Illuminate\Foundation\Http\FormRequest;

class PGharUpdate extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules= [
            'rooms'=>['required','integer','min:1'],
            'lat'=>['required','regex:/^\d{2}\.\d{6}$/'],
            'long'=>['required','regex:/^\d{2}\.\d{6}$/'],
            'p_image'=>['image','mimes:jpeg,jpg,png','max:400','nullable'],
            'remarks'=>['nullable','string']
        ];
        return $rules;
    }

     /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'rooms.required' => 'The number of rooms is required.',
            'rooms.integer' => 'Rooms must be a valid integer.',
            'rooms.min' => 'The number of rooms must be at least 1.',
            'lat.required' => 'Latitude is required.',
            'lat.regex' => 'Latitude must be in the format NN.NNNNNN.',
            'long.required' => 'Longitude is required.',
            'long.regex' => 'Longitude must be in the format NN.NNNNNN.',
            'p_image.required' => 'An image is required.',
            'p_image.image' => 'The file must be an image (jpeg, jpg, png).',
            'p_image.mimes' => 'Only jpeg, jpg, or png formats are allowed.',
            'p_image.max' => 'The image must be less than 400KB in size.',
        ];
    }
}
