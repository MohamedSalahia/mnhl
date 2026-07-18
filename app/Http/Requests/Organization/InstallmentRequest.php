<?php

namespace App\Http\Requests\Organization;

use App\Models\BranchStudent;
use App\Models\Installment;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InstallmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;

    }// end of authorize

    public function rules()
    {
        $organizationId = session('selected_organization')['id'] ?? null;

        $rules = [
            'organization_id' => ['required', 'exists:organizations,id'],
            'branch_id' => ['required', 'exists:branches,id'],
            'student_id' => ['required', 'exists:users,id'],
            'project_id' => [
                'required',
                Rule::exists('projects', 'id')->where('organization_id', $organizationId),
            ],
            'amount' => ['required', 'numeric', 'gt:0'],
            'payment_method_id' => [
                'required',
                'integer',
                Rule::exists(PaymentMethod::class, 'id')
                    ->where('organization_id', $organizationId)
                    ->withoutTrashed(),
            ],
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {

            $rules = [
                'amount' => ['required', 'numeric', 'gt:0'],
                'payment_method_id' => [
                    'required',
                    'integer',
                    Rule::exists(PaymentMethod::class, 'id')
                        ->where('organization_id', $organizationId)
                        ->withoutTrashed(),
                ],
            ];

        }//end of if

        return $rules;

    }// end of rules

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {

            if (in_array($this->method(), ['POST'])) {

                $studentId = (int) $this->student_id;
                $branchId = (int) $this->branch_id;
                $projectId = (int) $this->project_id;

            } elseif (in_array($this->method(), ['PUT', 'PATCH'])) {

                $installment = $this->route()->parameter('installment');

                if (! $installment instanceof Installment) {

                    return;

                }//end of if

                $studentId = (int) $installment->student_id;
                $branchId = (int) $installment->branch_id;
                $projectId = (int) $installment->project_id;

            } else {

                return;

            }//end of if

            if (! static::enrollmentAllowsInstallments($studentId, $branchId, $projectId)) {

                $validator->errors()->add(
                    in_array($this->method(), ['POST']) ? 'student_id' : 'amount',
                    __('installments.non_exempt_enrollment_required')
                );

                return;

            }//end of if

            $branchStudent = BranchStudent::query()
                ->whenStudentId($studentId)
                ->whenBranchId($branchId)
                ->whenProjectId($projectId)
                ->first();

            if ($branchStudent === null) {

                return;

            }//end of if

            $installment = null;

            if (in_array($this->method(), ['PUT', 'PATCH'])) {

                $installment = $this->route()->parameter('installment');

            }//end of if

            $maxAmount = static::maxInstallmentAmountForBranchStudent($branchStudent, $installment);

            $amount = round((float) $this->amount, 3);

            if ($amount > $maxAmount) {

                $validator->errors()->add('amount', __('installments.amount_exceeds_remaining'));

            }//end of if

        });

    }// end of withValidator

    public static function maxInstallmentAmountForBranchStudent(BranchStudent $branchStudent, ?Installment $installment = null): float
    {
        $remainingFees = round((float) $branchStudent->remaining_fees, 3);

        if ($installment === null) {

            return $remainingFees;

        }//end of if

        return round($remainingFees + (float) $installment->amount, 3);

    }// end of maxInstallmentAmountForBranchStudent

    public static function enrollmentAllowsInstallments(int $studentId, int $branchId, int $projectId): bool
    {
        return BranchStudent::query()
            ->whenStudentId($studentId)
            ->whenBranchId($branchId)
            ->whenProjectId($projectId)
            ->where('exempted_from_fees', false)
            ->exists();

    }// end of enrollmentAllowsInstallments

    protected function prepareForValidation(): void
    {
        $this->merge([
            'organization_id' => session('selected_organization')['id'] ?? null,
            'branch_id' => session('selected_branch')['id'] ?? null,
        ]);

    }// end of prepareForValidation

}// end of request
