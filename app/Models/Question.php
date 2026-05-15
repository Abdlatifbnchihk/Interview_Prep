<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    protected $fillable = ['question_generation_id', 'question'];

    public function generation(): BelongsTo
    {
        return $this->belongsTo(QuestionGeneration::class);
    }
}