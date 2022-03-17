<?php

declare(strict_types=1);

namespace Asseco\RemoteRelations\Tests;

use Asseco\RemoteRelations\GelfServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [GelfServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}
