<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fund extends Model
{
    use HasFactory;

    protected $fillable = ['organization_id', 'name'];

    // Scopes
    public function scopeWhenOrganizationId($query, $organizationId)
    {
        return $query->when($organizationId, fn($q) => $q->where('organization_id', $organizationId));
    }

    // Relationships
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function financialTransactions(): HasMany
    {
        return $this->hasMany(FinancialTransaction::class);
    }
}
