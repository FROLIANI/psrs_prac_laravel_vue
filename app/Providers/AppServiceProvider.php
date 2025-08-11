<?php

namespace App\Providers;
use App\models\Job;
use App\Policies\JobPolicy;
use Illuminate\Support\ServiceProvider;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
	 
	 protected $policies = [
    Job::class => JobPolicy::class,
];

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
