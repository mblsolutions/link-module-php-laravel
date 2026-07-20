<?php

declare(strict_types=1);

namespace Tests\Feature;

use GuzzleHttp\Exception\RequestException;
use MBLSolutions\LinkModule\Asset;
use MBLSolutions\LinkModule\AssetMetadata;
use MBLSolutions\LinkModule\Links;
use MBLSolutions\LinkModule\Serial;
use MBLSolutions\LinkModule\ShortCode;
use MBLSolutions\LinkModuleLaravel\Data\CancelLinksRequestData;
use MBLSolutions\LinkModuleLaravel\Data\CreateLinksRequestData;
use MBLSolutions\LinkModuleLaravel\Data\CreateLinksResponseData;
use MBLSolutions\LinkModuleLaravel\Data\ShowLinksGroupRequestData;
use MBLSolutions\LinkModuleLaravel\Data\UpdateLinksRequestData;
use MBLSolutions\LinkModuleLaravel\Exceptions\LinksModuleRequestException;
use MBLSolutions\LinkModuleLaravel\LinkModuleService;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\RequestInterface;
use Tests\TestCase;

class LinkModuleServiceTest extends TestCase
{
    private Links $linksClient;
    private Serial $serialClient;
    private ShortCode $shortCodeClient;
    private AssetMetadata $metadataClient;
    private Asset $assetClient;
    private LinkModuleService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->linksClient = \Mockery::mock(Links::class);
        $this->serialClient = \Mockery::mock(Serial::class);
        $this->shortCodeClient = \Mockery::mock(ShortCode::class);
        $this->metadataClient = \Mockery::mock(AssetMetadata::class);
        $this->assetClient = \Mockery::mock(Asset::class);

        $this->service = new LinkModuleService(
            linksClient: $this->linksClient,
            serialClient: $this->serialClient,
            shortCodeClient: $this->shortCodeClient,
            metadataClient: $this->metadataClient,
            assetClient: $this->assetClient,
        );
    }

    #[Test]
    public function it_creates_links_and_returns_a_create_links_response(): void
    {
        $request = new CreateLinksRequestData(
            uuid: 'test-uuid',
            expiration: now()->addDay(),
            callback: 'https://callback.example.com',
            quantity: 1,
        );

        $this->linksClient
            ->shouldReceive('create')
            ->once()
            ->andReturn([
                'reference_uuid' => 'test-uuid',
                'expiration' => now()->addDay()->toIso8601String(),
                'items' => [
                    ['uuid' => 'item-uuid-1', 'link' => 'https://link.example.com/item-uuid-1'],
                ],
            ]);

        $result = $this->service->create($request);

        $this->assertInstanceOf(CreateLinksResponseData::class, $result);
        $this->assertSame('test-uuid', $result->referenceUuid);
        $this->assertCount(1, $result->items);
    }

    #[Test]
    public function it_wraps_a_guzzle_request_exception_in_a_links_module_request_exception(): void
    {
        $this->expectException(LinksModuleRequestException::class);

        $psrRequest = $this->createMock(RequestInterface::class);
        $guzzleException = new RequestException('Connection refused', $psrRequest);

        $request = new CreateLinksRequestData(
            uuid: 'test-uuid',
            expiration: now()->addDay(),
            callback: 'https://callback.example.com',
        );

        $this->linksClient
            ->shouldReceive('create')
            ->once()
            ->andThrow($guzzleException);

        $this->service->create($request);
    }

    #[Test]
    public function it_shows_a_link_by_reference_and_item(): void
    {
        $this->linksClient
            ->shouldReceive('show')
            ->once()
            ->with('ref-uuid', 'item-uuid', [])
            ->andReturn([
                'uuid' => 'item-uuid',
                'value' => 'active',
                'meta' => null,
            ]);

        $result = $this->service->show('ref-uuid', 'item-uuid');

        $this->assertSame('item-uuid', $result->uuid);
        $this->assertNull($result->serial);
        $this->assertNull($result->short_code);
    }

    #[Test]
    public function it_shows_a_link_with_serial_and_short_code_when_included(): void
    {
        $this->linksClient
            ->shouldReceive('show')
            ->once()
            ->with('ref-uuid', 'item-uuid', ['X-Include-Short-Code' => 'true'])
            ->andReturn([
                'uuid' => 'item-uuid',
                'value' => 'active',
                'meta' => null,
                'serial' => '4300066247',
                'short_code' => 'OKhl3n37luAJcfDTGQwGTlKW',
            ]);

        $result = $this->service->show('ref-uuid', 'item-uuid', ['X-Include-Short-Code' => 'true']);

        $this->assertSame('item-uuid', $result->uuid);
        $this->assertSame('4300066247', $result->serial);
        $this->assertSame('OKhl3n37luAJcfDTGQwGTlKW', $result->short_code);
    }

    #[Test]
    public function it_shows_a_link_group(): void
    {
        $this->linksClient
            ->shouldReceive('showLinkGroup')
            ->once()
            ->andReturn([
                'reference_uuid' => 'ref-uuid',
                'expiration' => now()->addDay()->toIso8601String(),
                'items' => [],
            ]);

        $result = $this->service->showLinkGroup('ref-uuid', new ShowLinksGroupRequestData());

        $this->assertInstanceOf(CreateLinksResponseData::class, $result);
    }
}
