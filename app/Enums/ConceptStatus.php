<?php

namespace App\Enums;

enum ConceptStatus: string
{
    case ToReview   = 'to_review';
    case InProgress = 'in_progress';
    case Mastered   = 'mastered';

    public function label(): string
    {
        return match($this) {
            self::ToReview   => 'To Review',
            self::InProgress => 'In Progress',
            self::Mastered   => 'Mastered',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ToReview   => 'gray',
            self::InProgress => 'amber',
            self::Mastered   => 'green',
        };
    }
}