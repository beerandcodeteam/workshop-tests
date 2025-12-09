<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutPaymentRequest extends FormRequest
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
        return [
            'product_id' => 'required|exists:products,id',
            'address' => 'required|array',
            'address.street' => 'required|string|max:255',
            'address.number' => 'required|string|max:20',
            'address.city' => 'required|string|max:100',
            'address.state' => 'required|string|max:2',
            'address.zip' => 'required|string|max:10',
            'payment_method_id' => 'required|string',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'product_id.required' => 'O produto é obrigatório.',
            'product_id.uuid' => 'O identificador do produto é inválido.',
            'product_id.exists' => 'Produto não encontrado.',
            'address.required' => 'O endereço é obrigatório.',
            'address.street.required' => 'A rua é obrigatória.',
            'address.number.required' => 'O número é obrigatório.',
            'address.city.required' => 'A cidade é obrigatória.',
            'address.state.required' => 'O estado é obrigatório.',
            'address.state.max' => 'O estado deve ter no máximo 2 caracteres.',
            'address.zip.required' => 'O CEP é obrigatório.',
            'payment_method_id.required' => 'O método de pagamento é obrigatório.',
        ];
    }
}
