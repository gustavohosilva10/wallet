<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreContactRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type' => 'required|string|max:30',
            'contact' => 'required|string|max:150',
            'person_id' => 'required|exists:people,id',
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'The contact type is required.',
            'type.string' => 'The contact type must be a string.',
            'type.max' => 'The contact type may not be greater than 30 characters.',
            'contact.required' => 'The contact field is required.',
            'contact.string' => 'The contact must be a string.',
            'contact.max' => 'The contact may not be greater than 150 characters.',
            'person_id.required' => 'The person ID is required.',
            'person_id.exists' => 'The specified person does not exist.',
        ];
    }
}
