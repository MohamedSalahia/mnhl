<?php

namespace App\Models;

use App\Enums\FinancialTransactionTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id', 'branch_id',
        'type', 'date', 'description', 'amount',
        'currency_id', 'fund_id',
    ];

    protected $casts = [
        'type'   => FinancialTransactionTypeEnum::class,
        'date'   => 'date',
        'amount' => 'decimal:3',
    ];

    // Scopes
    public function scopeWhenOrganizationId($query, $organizationId)
    {
        return $query->when($organizationId, fn($q) => $q->where('organization_id', $organizationId));
    }

    public function scopeWhenBranchId($query, $branchId)
    {
        return $query->when($branchId, fn($q) => $q->where('branch_id', $branchId));
    }

    // Relationships
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function fund(): BelongsTo
    {
        return $this->belongsTo(Fund::class);
    }

    // Helpers
    public function isIncome(): bool  { return $this->type === FinancialTransactionTypeEnum::INCOME; }
    public function isExpense(): bool { return $this->type === FinancialTransactionTypeEnum::EXPENSE; }
}
