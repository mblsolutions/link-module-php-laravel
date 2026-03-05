<?php

declare(strict_types=1);

namespace Tests\Unit\Auth;

use Illuminate\Support\Facades\Cache;
use MBLSolutions\LinkModule\Api\AccessToken;
use MBLSolutions\LinkModule\Auth\TokenResolver;
use MBLSolutions\LinkModuleLaravel\Auth\CachedTokenResolver;
use PHPUnit\Framework\Attributes\Test;
use ReflectionProperty;
use Tests\TestCase;

class CachedTokenResolverTest extends TestCase
{
    private const CACHE_KEY = 'test-link-module-token';

    private CachedTokenResolver $resolver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resolver = new CachedTokenResolver(
            tokenUri: 'https://auth.example.com/token',
            clientId: 'test-client-id',
            clientSecret: 'test-client-secret',
            cacheKey: self::CACHE_KEY,
        );
    }

    #[Test]
    public function it_returns_a_valid_cached_token_without_making_a_new_request(): void
    {
        $accessToken = $this->makeAccessToken(expiresIn: 3600, expired: false);

        Cache::shouldReceive('get')
            ->once()
            ->with(self::CACHE_KEY)
            ->andReturn($accessToken);

        $result = $this->resolver->resolveToken();

        $this->assertSame($accessToken, $result);
    }

    #[Test]
    public function it_fetches_a_new_token_when_the_cache_is_empty(): void
    {
        $accessToken = $this->makeAccessToken(expiresIn: 3600, expired: false);

        Cache::shouldReceive('get')
            ->once()
            ->with(self::CACHE_KEY)
            ->andReturnNull();

        Cache::shouldReceive('put')
            ->once()
            ->with(self::CACHE_KEY, $accessToken, 3540);

        $this->mockBaseTokenResolver($accessToken);

        $result = $this->resolver->resolveToken();

        $this->assertSame($accessToken, $result);
    }

    #[Test]
    public function it_flushes_an_expired_cached_token_and_fetches_a_new_one(): void
    {
        $expiredToken = $this->makeAccessToken(expiresIn: 0, expired: true);
        $freshToken = $this->makeAccessToken(expiresIn: 3600, expired: false);

        Cache::shouldReceive('get')
            ->once()
            ->with(self::CACHE_KEY)
            ->andReturn($expiredToken);

        Cache::shouldReceive('forget')
            ->once()
            ->with(self::CACHE_KEY);

        Cache::shouldReceive('put')
            ->once()
            ->with(self::CACHE_KEY, $freshToken, 3540);

        $this->mockBaseTokenResolver($freshToken);

        $result = $this->resolver->resolveToken();

        $this->assertSame($freshToken, $result);
    }

    #[Test]
    public function it_flushes_a_non_access_token_cache_entry_and_fetches_a_new_one(): void
    {
        $freshToken = $this->makeAccessToken(expiresIn: 3600, expired: false);

        Cache::shouldReceive('get')
            ->once()
            ->with(self::CACHE_KEY)
            ->andReturn('not-an-access-token');

        Cache::shouldReceive('forget')
            ->once()
            ->with(self::CACHE_KEY);

        Cache::shouldReceive('put')
            ->once()
            ->with(self::CACHE_KEY, $freshToken, 3540);

        $this->mockBaseTokenResolver($freshToken);

        $result = $this->resolver->resolveToken();

        $this->assertSame($freshToken, $result);
    }

    private function makeAccessToken(int $expiresIn, bool $expired): AccessToken
    {
        $token = \Mockery::mock(AccessToken::class);
        $token->expiresIn = $expiresIn;
        $token->shouldReceive('isExpired')->andReturn($expired);

        return $token;
    }

    private function mockBaseTokenResolver(AccessToken $token): void
    {
        $mockResolver = \Mockery::mock(TokenResolver::class);
        $mockResolver->shouldReceive('resolveToken')->once()->andReturn($token);

        $prop = new ReflectionProperty(CachedTokenResolver::class, 'baseTokenResolver');
        $prop->setValue($this->resolver, $mockResolver);
    }
}
