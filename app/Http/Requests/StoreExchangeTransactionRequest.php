<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExchangeTransactionRequest extends FormRequest
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
            'wallet_address' => ['required', 'string'],
            'token_from' => ['required', 'string'],
            'token_to' => ['required', 'string'],
            'amount_from' => ['required', 'numeric'],
            'amount_to' => ['required', 'numeric'],
            'chain_id' => ['required', 'numeric'],
            'transaction_hash' => ['required', 'string'],
        ];
    }
}
