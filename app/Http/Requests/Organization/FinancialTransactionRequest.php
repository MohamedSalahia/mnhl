<?php

namespace App\Http\Requests\Organization;

use App\Enums\FinancialTransactionTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FinancialTransactionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $types = array_column(FinancialTransactionTypeEnum::cases(), 'value');

        return [
            'type'        => ['required', Rule::in($types)],
            'date'        => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:1000'],
            'amount'      => ['required', 'numeric', 'gt:0'],
            'currency_id' => ['nullable', 'exists:currencies,id'],
            'fund_id'     => ['nullable', 'exists:funds,id'],
        ];
    }
}
