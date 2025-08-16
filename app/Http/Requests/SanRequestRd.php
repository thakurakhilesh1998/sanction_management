<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SanRequestRd extends FormRequest
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
        $validFY=['2020-21','2021-22','2022-23','2023-24','2024-25','2025-26'];
         $rules=[
            'financial_year'=>['required','string','in:'.implode(',',$validFY)],
            'district'=>['required','string'],
            'block'=>['required','string'],
            'san_amount'=>['required','numeric', 'min:0'],
            'sanction_date'=>['required','date'],
            'sanction_head'=>['required','string'],
            'sanction_purpose'=>['required','string'],
            'agency'=>['required','string']
           ];
           return $rules;
    }
}
