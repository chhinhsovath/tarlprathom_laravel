<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Student::class => \App\Policies\StudentPolicy::class,
        \App\Models\Assessment::class => \App\Policies\AssessmentPolicy::class,
        \App\Models\MentoringVisit::class => \App\Policies\MentoringVisitPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
