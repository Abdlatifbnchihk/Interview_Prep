<?php

namespace App\Models;

use App\Enums\ConceptDifficulty;
use App\Enums\ConceptStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Concept extends Model
{
    protected $fillable = ['domain_id', 'title', 'explanation', 'difficulty', 'status'];

    protected $casts = [
        'difficulty' => ConceptDifficulty::class,
        'status' => ConceptStatus::class,
    ];

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    public function generations(): HasMany
    {
        return $this->hasMany(QuestionGeneration::class);
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeByDifficulty(Builder $query, string $difficulty): Builder
    {
        return $query->where('difficulty', $difficulty);
    }

    public function scopeByDomain(Builder $query, int $domainId): Builder
    {
        return $query->where('domain_id', $domainId);
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->whereHas('domain', fn($q) => $q->where('user_id', $userId));
    }
}