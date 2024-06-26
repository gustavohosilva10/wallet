<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @OA\Schema(
 *     schema="UpdatePersonRequest",
 *     required={"name", "contacts"},
 *     @OA\Property(property="name", type="string", example="Fulano de Tal"),
 *     @OA\Property(
 *         property="contacts",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             required={"type", "contact"},
 *             @OA\Property(property="type", type="string", enum={"phone", "email", "whatsapp"}),
 *             @OA\Property(property="contact", type="string", example="fulano@example.com")
 *         )
 *     )
 * )
 */
class UpdatePersonRequest extends FormRequest
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

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:125',
            'contacts' => 'sometimes|array',
            'contacts.*.type' => 'required_with:contacts|string|in:phone,email,whatsapp',
            'contacts.*.contact' => 'required_with:contacts|string|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name field must be a string.',
            'name.max' => 'The name field may not be greater than 255 characters.',
            'contacts.array' => 'The contacts field must be an array.',
            'contacts.*.type.required_with' => 'The contact type is required when contacts are present.',
            'contacts.*.type.string' => 'The contact type must be a string.',
            'contacts.*.type.in' => 'The contact type must be one of the following: phone, email, whatsapp.',
            'contacts.*.contact.required_with' => 'The contact detail is required when contacts are present.',
            'contacts.*.contact.string' => 'The contact detail must be a string.',
            'contacts.*.contact.max' => 'The contact detail may not be greater than 255 characters.'
        ];
    }
}
