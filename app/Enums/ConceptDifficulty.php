<?php

namespace App\Enums;

enum ConceptDifficulty: string
{
    case Junior = 'junior';
    case Mid    = 'mid';
    case Senior = 'senior';

    public function label(): string
    {
        return match($this) {
            self::Junior => 'Junior',
            self::Mid    => 'Mid-level',
            self::Senior => 'Senior',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Junior => 'green',
            self::Mid    => 'amber',
            self::Senior => 'red',
        };
    }
}