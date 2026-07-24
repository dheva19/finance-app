<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TransactionStoreRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric',
            'type' => 'required|in:income,expense,transfer',
            'category_id' => 'nullable|numeric|exists:categories,id',
            'note' => 'nullable|string',
            'from_pocket_id' => 'nullable|numeric|exists:pockets,id',
            'to_pocket_id' => 'nullable|numeric|exists:pockets,id'
        ];
    }
}
