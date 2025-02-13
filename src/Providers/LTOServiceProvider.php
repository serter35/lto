<?php

namespace SerterSerbest\LTO\Providers;

use Illuminate\Support\ServiceProvider;
use SerterSerbest\LTO\Commands\CreateDTOCommand;
use SerterSerbest\LTO\Contracts\LTOContract;

class LTOServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->beforeResolving(LTOContract::class, function (string $dtoClassName) {
            /** @var class-string $dtoClassName */
            $dtoClassName::fromContainer($this->app);
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateDTOCommand::class,
            ]);
        }
    }
}
