<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Person\Contracts\PersonInterface;
use App\Infraestructure\Repositories\PersonRepository;
use App\Domain\Contact\Contracts\ContactInterface;
use App\Infraestructure\Repositories\ContactRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PersonInterface::class, PersonRepository::class);
        $this->app->bind(ContactInterface::class, ContactRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
