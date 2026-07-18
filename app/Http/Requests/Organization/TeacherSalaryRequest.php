<?php

namespace App\Http\Requests\Organization;

use App\Enums\TeacherSalaryTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeacherSalaryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $types = array_column(TeacherSalaryTypeEnum::cases(), 'value');

        return match ($this->method()) {
            'PUT', 'PATCH' => [
                'amount'            => ['required', 'numeric', 'gt:0'],
                'payment_method_id' => ['nullable', Rule::requiredIf(fn() => $this->type === 'payment'), 'exists:payment_methods,id'],
                'notes'             => ['nullable', 'string', 'max:500'],
                'date'              => ['nullable', 'date'],
                'period'            => ['nullable', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/'],
            ],
            default => [
                'organization_id'   => ['required', 'exists:organizations,id'],
                'branch_id'         => ['required', 'exists:branches,id'],
                'teacher_id'        => ['required', 'exists:users,id'],
                'type'              => ['required', Rule::in($types)],
                'amount'            => ['required', 'numeric', 'gt:0'],
                'payment_method_id' => ['nullable', Rule::requiredIf(fn() => $this->type === 'payment'), 'exists:payment_methods,id'],
                'notes'             => ['nullable', 'string', 'max:500'],
                'date'              => ['nullable', 'date'],
                'period'            => ['nullable', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/'],
                'carry_forward'     => ['nullable', 'boolean'],
            ],
        };
    }
}
