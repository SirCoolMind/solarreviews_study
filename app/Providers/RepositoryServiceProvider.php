<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\LeadRepositoryInterface;
use App\Repositories\LeadRepository;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
      $this->app->bind(LeadRepositoryInterface::class, LeadRepository::class);
   }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
