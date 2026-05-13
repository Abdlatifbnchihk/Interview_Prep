<?php

namespace App\Providers;

use App\Models\Domain;
use App\Policies\DomainPolicy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->registerPolicies();
    }

    protected function registerPolicies(): void
    {
        \Gate::policy(Domain::class, DomainPolicy::class);
    }
}