<?php

namespace MBLSolutions\LinkModuleLaravel;

use Illuminate\Support\ServiceProvider;
use MBLSolutions\LinkModule\Auth\LinkModule;

class LinkModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/link-module.php'
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/link-module.php', 'link-module'
        );

        LinkModule::setBaseUri($this->app['config']['link-module.endpoint']);
        LinkModule::setVerifySSL($this->app['config']['link-module.verify_ssl']);
    }
}