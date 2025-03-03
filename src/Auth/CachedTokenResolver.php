<?php

namespace MBLSolutions\LinkModuleLaravel\Auth;

use Illuminate\Support\Facades\Cache;
use MBLSolutions\LinkModule\Api\AccessToken;
use MBLSolutions\LinkModule\Auth\Contracts\TokenResolverInterface;
use MBLSolutions\LinkModule\Auth\TokenResolver;

class CachedTokenResolver implements TokenResolverInterface
{
    public TokenResolver $baseTokenResolver;

    public function __construct(
        string $tokenUri,

        string $clientId,

        string $clientSecret,

        private string $cacheKey
    )
    {
        $this->baseTokenResolver = new TokenResolver(
            tokenUri: $tokenUri,
            clientId: $clientId,
            clientSecret: $clientSecret
        );
    }

    public function resolveToken(): AccessToken
    {
        $cacheHit = Cache::get($this->cacheKey);

        // If the token is in the cache but not what we expect or expired, remove it
        if ($cacheHit && (!( $cacheHit instanceof AccessToken) || $cacheHit->isExpired())) {
            Cache::forget($this->cacheKey);
            $cacheHit = null;
        }

        if (! $cacheHit) {
            $accessToken = $this->baseTokenResolver->resolveToken();

            Cache::set($this->cacheKey, $accessToken, $accessToken->expiresIn - 60);
        } else {
            $accessToken = $cacheHit;
        }

        return $accessToken;
    }
}