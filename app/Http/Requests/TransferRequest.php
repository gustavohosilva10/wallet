<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @OA\Schema(
 *     schema="TransferRequest",
 *     required={"value", "payer", "payeer"},
 *     @OA\Property(property="value", type="number", format="float", example=100.50),
 *     @OA\Property(property="payer", type="integer", example=1),
 *     @OA\Property(property="payeer", type="integer", example=2)
 * )
 */
class TransferRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

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
        return [
            'value' => 'required|numeric|min:0.01',
            'payer' => 'required|exists:users,id',
            'payeer' => 'required|exists:users,id',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'value.required' => 'O campo valor é obrigatório.',
            'value.numeric' => 'O campo valor deve ser numérico.',
            'value.min' => 'O campo valor deve ser pelo menos 0.01.',

            'payer.required' => 'O campo pagador é obrigatório.',
            'payer.exists' => 'O pagador informado não existe.',

            'payeer.required' => 'O campo lojista é obrigatório.',
            'payeer.exists' => 'O lojista informado não existe.',
        ];
    }
}
