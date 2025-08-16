<?php

namespace App\Http\Requests\Progress;

use Illuminate\Foundation\Http\FormRequest;

class ProgressData extends FormRequest
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
        // $progressOption=['Tender Floated','Tender Cancelled','Tender Awarded','Work Started'];
        $rules=
        [
            'completion_percentage' => ['string'],
            // 'p_isComplete'=>['required','string'],
            // 'p_uc'=> ['nullable','file','mimes:pdf','max:2048'],
            'work_started_image'=>['nullable','image','mimes:jpeg,jpg,png','max:400'],
            'work_partial_image'=>['nullable','image','mimes:jpeg,jpg,png','max:400'],
            'work_completed_image'=>['nullable','image','mimes:jpeg,jpg,png','max:400'],
            'remarks'=>['nullable','string'],
            'gp'=>['required'],
            'block'=>['required','string'],
            'district'=>['required','string'],

        ];  
        return $rules;
    }
}
