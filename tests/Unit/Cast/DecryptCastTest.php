<?php

declare(strict_types=1);

namespace Tests\Unit\Cast;

use MBLSolutions\LinkModuleLaravel\Cast\DecryptCast;
use MBLSolutions\LinkModuleLaravel\LinkDecryptionService;
use PHPUnit\Framework\Attributes\Test;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Tests\TestCase;

class DecryptCastTest extends TestCase
{
    private DecryptCast $cast;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cast = new DecryptCast();
    }

    #[Test]
    public function it_returns_null_when_value_is_null(): void
    {
        $result = $this->cast->cast(
            $this->makeDataProperty(),
            null,
            [],
            $this->makeCreationContext()
        );

        $this->assertNull($result);
    }

    #[Test]
    public function it_returns_null_when_decryption_service_is_not_bound(): void
    {
        // Explicitly ensure the service is not bound regardless of config state
        $this->app->offsetUnset(LinkDecryptionService::class);

        $result = $this->cast->cast(
            $this->makeDataProperty(),
            'some-encrypted-value',
            [],
            $this->makeCreationContext()
        );

        $this->assertNull($result);
    }

    #[Test]
    public function it_decrypts_the_value_when_the_service_is_bound(): void
    {
        $decryptionService = \Mockery::mock(LinkDecryptionService::class);
        $decryptionService->shouldReceive('decrypt')
            ->once()
            ->with('encrypted')
            ->andReturn('decrypted');

        $this->app->instance(LinkDecryptionService::class, $decryptionService);

        $result = $this->cast->cast(
            $this->makeDataProperty(),
            'encrypted',
            [],
            $this->makeCreationContext()
        );

        $this->assertSame('decrypted', $result);
    }

    private function makeDataProperty(): DataProperty
    {
        return \Mockery::mock(DataProperty::class);
    }

    private function makeCreationContext(): CreationContext
    {
        return \Mockery::mock(CreationContext::class);
    }
}
