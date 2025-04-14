<?php

namespace App\Providers;

use App\Interfaces\CandidateRepositoryInterface;
use App\Repositories\ApiCandidateRepository;
use App\Repositories\CandidateRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(
            CandidateRepositoryInterface::class, 
            CandidateRepository::class
            // ApiCandidateRepository::class
        );
    }
}
