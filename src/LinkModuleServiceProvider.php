<?php

namespace MBLSolutions\LinkModuleLaravel;

use Illuminate\Support\ServiceProvider;
use MBLSolutions\LinkModule\Auth\LinkModule;
use MBLSolutions\LinkModule\Links;
use MBLSolutions\LinkModuleLaravel\Auth\CachedTokenResolver;

class LinkModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/link-module.php' => config_path('link-module.php')
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/link-module.php',
            'link-module'
        );

        LinkModule::setBaseUri($this->app['config']['link-module.endpoint']);
        LinkModule::setVerifySSL($this->app['config']['link-module.verify_ssl']);

        $this->app->singleton(CachedTokenResolver::class, function () {
            return new CachedTokenResolver(
                tokenUri: $this->app['config']['link-module.auth.token_url'],
                clientId: $this->app['config']['link-module.auth.client_id'],
                clientSecret: $this->app['config']['link-module.auth.client_secret'],
                cacheKey: $this->app['config']['link-module.auth.token_cache_key'],
            );
        });

        $this->app->singleton(LinkModuleService::class, function () {

            if (! $this->app['config']['link-module.auth.enabled']) {
                LinkModule::disableToken();
            } else {
                LinkModule::setTokenResolver(
                    $this->app->make(CachedTokenResolver::class)
                );
            }

            return new LinkModuleService(
                linksClient: $this->app->make(Links::class),
            );
        });
    }
}
