<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id', 'branch_id', 'student_id', 'project_id', 'payment_method_id', 'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    //attr

    //scope
    public function scopeWhenOrganizationId($query, $organizationId)
    {
        return $query->when($organizationId, function ($q) use ($organizationId) {

            return $q->where('organization_id', $organizationId);

        });

    }// end of scopeWhenOrganizationId

    public function scopeWhenBranchId($query, $branchId)
    {
        return $query->when($branchId, function ($q) use ($branchId) {

            return $q->where('branch_id', $branchId);

        });

    }// end of scopeWhenBranchId

    public function scopeWhenStudentId($query, $studentId)
    {
        return $query->when($studentId, function ($q) use ($studentId) {

            return $q->where('student_id', $studentId);

        });

    }// end of scopeWhenStudentId

    public function scopeWhenProjectId($query, $projectId)
    {
        return $query->when($projectId, function ($q) use ($projectId) {

            return $q->where('project_id', $projectId);

        });

    }// end of scopeWhenProjectId

    //rel
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);

    }// end of organization

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);

    }// end of branch

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');

    }// end of student

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);

    }// end of project

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);

    }// end of paymentMethod

    //fun
    protected static function boot(): void
    {
        parent::boot();

        static::created(function (Installment $installment) {

            static::syncBranchStudentFeesForContext(
                $installment->student_id,
                $installment->branch_id,
                $installment->project_id
            );

        });

        static::updated(function (Installment $installment) {

            if ($installment->wasChanged(['student_id', 'branch_id', 'project_id'])) {

                static::syncBranchStudentFeesForContext(
                    $installment->getOriginal('student_id'),
                    $installment->getOriginal('branch_id'),
                    $installment->getOriginal('project_id')
                );

            }//end of if

            static::syncBranchStudentFeesForContext(
                $installment->student_id,
                $installment->branch_id,
                $installment->project_id
            );

        });

        static::deleted(function (Installment $installment) {

            static::syncBranchStudentFeesForContext(
                $installment->student_id,
                $installment->branch_id,
                $installment->project_id
            );

        });

    }// end of boot

    public static function syncBranchStudentFeesForContext(?int $studentId, ?int $branchId, ?int $projectId): void
    {
        if ($studentId === null || $branchId === null || $projectId === null) {

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

        if ($branchStudent->exempted_from_fees) {

            $branchStudent->update([
                'paid_fees' => 0,
                'remaining_fees' => 0,
            ]);

            return;

        }//end of if

        $paidTotal = (float) Installment::query()
            ->whenStudentId($studentId)
            ->whenBranchId($branchId)
            ->whenProjectId($projectId)
            ->sum('amount');

        $feesTotal = (float) $branchStudent->fees;
        $remaining = max(0, $feesTotal - $paidTotal);

        $branchStudent->update([
            'paid_fees' => $paidTotal,
            'remaining_fees' => $remaining,
        ]);

    }// end of syncBranchStudentFeesForContext

} //end of model
