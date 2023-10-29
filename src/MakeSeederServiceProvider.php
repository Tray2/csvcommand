<?php

namespace Tray2\MakeSeeder;

use Illuminate\Support\ServiceProvider;
use Tray2\MakeSeeder\Commands\MakeSeederCommand;

class MakeSeederServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(commands: [
                MakeSeederCommand::class,
            ]);
        }
    }
}