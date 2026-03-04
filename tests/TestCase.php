<?php

declare(strict_types=1);

namespace Tests;

use MBLSolutions\LinkModuleLaravel\LinkModuleServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            \Spatie\LaravelData\LaravelDataServiceProvider::class,
            LinkModuleServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('link-module.endpoint', 'https://test.example.com');
        $app['config']->set('link-module.verify_ssl', false);
        $app['config']->set('link-module.auth.enabled', false);
        $app['config']->set('link-module.auth.token_url', 'https://auth.example.com/token');
        $app['config']->set('link-module.auth.client_id', 'test-client-id');
        $app['config']->set('link-module.auth.client_secret', 'test-client-secret');
        $app['config']->set('link-module.auth.token_cache_key', 'test-link-module-token');
        $app['config']->set('link-module.decrypt.enabled', false);
        $app['config']->set('link-module.decrypt.cipher', 'chacha20');
        $app['config']->set('link-module.decrypt.key', '');
        $app['config']->set('link-module.decrypt.iv', '');
    }
}
