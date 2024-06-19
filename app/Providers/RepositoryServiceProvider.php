<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Users\Contracts\UserInterface;
use App\Infraestructure\Repositories\UserRepository;
use App\Domain\Transfers\Contracts\TransferInterface;
use App\Infraestructure\Repositories\TransferRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(TransferInterface::class, TransferRepository::class);
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
