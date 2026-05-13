<?php

namespace App\Models;

use App\Enums\ConceptStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Domain extends Model
{
    protected $fillable = ['name', 'color', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function concepts(): HasMany
    {
        return $this->hasMany(Concept::class);
    }

    public function masteredCount(): int
    {
        return $this->concepts()->where('status', ConceptStatus::Mastered)->count();
    }

    public function inProgressCount(): int
    {
        return $this->concepts()->where('status', ConceptStatus::InProgress)->count();
    }

    public function toReviewCount(): int
    {
        return $this->concepts()->where('status', ConceptStatus::ToReview)->count();
    }
}