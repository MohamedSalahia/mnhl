<?php

namespace App\Models;

use App\Enums\TeacherSalaryTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherSalary extends Model
{
    use HasFactory;

    protected $table = 'teacher_salary_transactions';

    protected $fillable = [
        'organization_id', 'branch_id', 'teacher_id',
        'type', 'amount', 'payment_method_id', 'notes', 'date',
        'period_year', 'period_month', 'carry_forward',
    ];

    protected $casts = [
        'amount'        => 'decimal:3',
        'type'          => TeacherSalaryTypeEnum::class,
        'date'          => 'date',
        'carry_forward' => 'boolean',
    ];

    //scope
    public function scopeWhenOrganizationId($query, $organizationId)
    {
        return $query->when($organizationId, fn($q) => $q->where('organization_id', $organizationId));
    }

    public function scopeWhenBranchId($query, $branchId)
    {
        return $query->when($branchId, fn($q) => $q->where('branch_id', $branchId));
    }

    public function scopeWhenTeacherId($query, $teacherId)
    {
        return $query->when($teacherId, fn($q) => $q->where('teacher_id', $teacherId));
    }

    public function scopeOfType($query, TeacherSalaryTypeEnum $type)
    {
        return $query->where('type', $type->value);
    }

    //rel
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    //helpers
    public function isPayment(): bool   { return $this->type === TeacherSalaryTypeEnum::PAYMENT; }
    public function isBonus(): bool     { return $this->type === TeacherSalaryTypeEnum::BONUS; }
    public function isDeduction(): bool { return $this->type === TeacherSalaryTypeEnum::DEDUCTION; }
    public function isAdvance(): bool   { return $this->type === TeacherSalaryTypeEnum::ADVANCE; }
}
