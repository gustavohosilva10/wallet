<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @OA\Schema(
 *     schema="BracketRequest",
 *     type="object",
 *     required={"brackets"},
 *     @OA\Property(
 *         property="brackets",
 *         type="string",
 *         description="Sequence of brackets to be validated",
 *         example="(){}[]"
 *     )
 * )
 */
class BracketRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'brackets' => 'required|string|regex:/^[\[\]\(\)\{\}]+$/'
        ];

    }

    public function messages()
    {
        return [
            'brackets.required' => 'The brackets field is required.',
            'brackets.string' => 'The brackets field must be a string.',
            'brackets.regex' => 'The brackets field must contain only valid bracket characters: (), {}, [].'
        ];
    }
}
