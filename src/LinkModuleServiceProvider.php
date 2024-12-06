<?php

namespace MBLSolutions\LinkModuleLaravel;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use MBLSolutions\LinkModule\Api\OAuthResource;
use MBLSolutions\LinkModule\Auth\LinkModule;
use MBLSolutions\LinkModule\Links;

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
            __DIR__ . '/../config/link-module.php',
            'link-module'
        );

        LinkModule::setBaseUri($this->app['config']['link-module.endpoint']);
        LinkModule::setVerifySSL($this->app['config']['link-module.verify_ssl']);

        $this->app->singleton(LinkModuleService::class, function () {
            $cacheHit = Cache::get($this->app['config']['link-module.auth.token_cache_key']);

            if ($this->app['config']['link-module.static_token']) {
                $fullTokenString = $this->app['config']['link-module.static_token'];
            } else if (! $cacheHit) {
                $authClient = new OAuthResource(
                    tokenUri: $this->app['config']['link-module.auth.token_url'],
                    clientId: $this->app['config']['link-module.auth.client_id'],
                    clientSecret: $this->app['config']['link-module.auth.client_secret']
                );

                $accessToken = $authClient->getToken();

                $fullTokenString = $accessToken->tokenType . ' ' . $accessToken->accessToken;

                Cache::set($this->app['config']['link-module.auth.token_cache_key'], $fullTokenString, $accessToken->expiresIn - 60);
            } else {
                $fullTokenString = $cacheHit;
            }

            LinkModule::setToken($fullTokenString);

            return new LinkModuleService(
                linksClient: $this->app->make(Links::class),
            );
        });
    }
}
