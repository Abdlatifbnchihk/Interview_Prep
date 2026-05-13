<?php

namespace App\Policies;

use App\Models\QuestionGeneration;
use App\Models\User;

class QuestionGenerationPolicy
{
    public function view(User $user, QuestionGeneration $generation): bool
    {
        return $user->id === $generation->concept->domain->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function delete(User $user, QuestionGeneration $generation): bool
    {
        return $user->id === $generation->concept->domain->user_id;
    }
}