<?php

declare(strict_types=1);

namespace Tests\Unit\Exceptions;

use MBLSolutions\LinkModuleLaravel\Data\ErrorResponseData;
use MBLSolutions\LinkModuleLaravel\Data\StatusEnum;
use MBLSolutions\LinkModuleLaravel\Exceptions\LinksModuleRequestException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class LinksModuleRequestExceptionTest extends TestCase
{
    #[Test]
    public function it_uses_the_error_details_message_when_available(): void
    {
        $errorDetails = new ErrorResponseData(StatusEnum::Error, 'Something went wrong');
        $request = $this->createMock(RequestInterface::class);

        $exception = new LinksModuleRequestException(
            errorDetails: $errorDetails,
            request: $request,
        );

        $this->assertSame('Something went wrong', $exception->getMessage());
    }

    #[Test]
    public function it_uses_the_response_body_when_no_error_details_are_present(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('rewind');
        $stream->method('getContents')->willReturn('{"error":"bad request"}');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);

        $exception = new LinksModuleRequestException(
            errorDetails: null,
            request: $request,
            response: $response,
        );

        $this->assertStringContainsString('{"error":"bad request"}', $exception->getMessage());
    }

    #[Test]
    public function it_uses_the_default_message_when_no_details_or_response_are_present(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $exception = new LinksModuleRequestException(
            errorDetails: null,
            request: $request,
        );

        $this->assertSame('An error occurred with the Links Module API', $exception->getMessage());
    }

    #[Test]
    public function it_stores_the_error_details_and_request_on_the_exception(): void
    {
        $errorDetails = new ErrorResponseData(StatusEnum::Error, 'Test error');
        $request = $this->createMock(RequestInterface::class);

        $exception = new LinksModuleRequestException(
            errorDetails: $errorDetails,
            request: $request,
            code: 422,
        );

        $this->assertSame($errorDetails, $exception->errorDetails);
        $this->assertSame($request, $exception->request);
        $this->assertSame(422, $exception->getCode());
    }
}
