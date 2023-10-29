<?php

namespace Tests;

use Orchestra\Testbench\TestCase;
use Tray2\MakeSeeder\MakeSeederServiceProvider;

class PackageTestCase extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
          MakeSeederServiceProvider::class,
        ];
    }
}