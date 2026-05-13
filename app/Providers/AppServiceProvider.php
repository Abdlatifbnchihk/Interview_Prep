<?php

namespace App\Providers;

use App\Models\Concept;
use App\Models\Domain;
use App\Models\QuestionGeneration;
use App\Policies\ConceptPolicy;
use App\Policies\DomainPolicy;
use App\Policies\QuestionGenerationPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Domain::class, DomainPolicy::class);
        Gate::policy(Concept::class, ConceptPolicy::class);
        Gate::policy(QuestionGeneration::class, QuestionGenerationPolicy::class);
    }
}