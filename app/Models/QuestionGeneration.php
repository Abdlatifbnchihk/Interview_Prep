<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionGeneration extends Model
{
    protected $fillable = ['concept_id'];

    public function concept(): BelongsTo
    {
        return $this->belongsTo(Concept::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}