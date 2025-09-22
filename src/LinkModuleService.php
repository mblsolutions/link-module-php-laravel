<?php

namespace MBLSolutions\LinkModuleLaravel;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use MBLSolutions\LinkModule\AssetMetadata;
use MBLSolutions\LinkModule\Exceptions\AuthenticationException;
use MBLSolutions\LinkModule\Links as LinksClient;
use MBLSolutions\LinkModule\Serial;
use MBLSolutions\LinkModule\ShortCode;
use MBLSolutions\LinkModuleLaravel\Data\AllocateLinkRequestData;
use MBLSolutions\LinkModuleLaravel\Data\CreateLinksResponseData;
use MBLSolutions\LinkModuleLaravel\Data\CreateLinksRequestData;
use MBLSolutions\LinkModuleLaravel\Data\CancelLinksResponseData;
use MBLSolutions\LinkModuleLaravel\Data\CancelLinksRequestData;
use MBLSolutions\LinkModuleLaravel\Data\ErrorResponseData;
use MBLSolutions\LinkModuleLaravel\Data\RedeemLinkResponseData;
use MBLSolutions\LinkModuleLaravel\Data\ShowLinkResponseData;
use MBLSolutions\LinkModuleLaravel\Data\ShowLinksGroupRequestData;
use MBLSolutions\LinkModuleLaravel\Data\UpdateLinksBySerialRequestData;
use MBLSolutions\LinkModuleLaravel\Data\UpdateLinksByShortCodeRequestData;
use MBLSolutions\LinkModuleLaravel\Data\UpdateLinksRequestData;
use MBLSolutions\LinkModuleLaravel\Data\UpdateLinksResponseData;
use MBLSolutions\LinkModuleLaravel\Exceptions\LinksModuleRequestException;

class LinkModuleService
{
    public function __construct(
        private readonly LinksClient $linksClient,
        private readonly Serial $serialClient,
        private readonly ShortCode $shortCodeClient,
        private readonly AssetMetadata $metadataClient
    ) {}

    public function create(CreateLinksRequestData $createLinksRequest, array $headers = []): CreateLinksResponseData
    {
        return $this->handleExceptions(function () use ($createLinksRequest, $headers): CreateLinksResponseData {
            return CreateLinksResponseData::fromArray(
                $this->linksClient->create(
                    $createLinksRequest->toArray(),
                    $headers
                )
            );
        });
    }

    public function allocate(AllocateLinkRequestData $allocateLinkRequest, array $headers = []): CreateLinksResponseData
    {
        return $this->handleExceptions(function () use ($allocateLinkRequest, $headers): CreateLinksResponseData {
            return CreateLinksResponseData::fromArray(
                $this->linksClient->allocate(
                    $allocateLinkRequest->toArray(),
                    $headers
                )
            );
        });
    }

    public function show(string $reference, string $item, array $headers = []): ShowLinkResponseData
    {
        return $this->handleExceptions(function () use ($reference, $item, $headers): ShowLinkResponseData {
            return ShowLinkResponseData::from(
                $this->linksClient->show($reference, $item, $headers)
            );
        });
    }

    public function redeem(string $reference, string $item, array $headers = []): RedeemLinkResponseData|ShowLinkResponseData
    {
        return $this->handleExceptions(function () use ($reference, $item, $headers): RedeemLinkResponseData|ShowLinkResponseData {
            $response = $this->linksClient->redeem($reference, $item, $headers);

            if (array_key_exists('status', $response)) {
                return RedeemLinkResponseData::from(
                    $response
                );
            } else {
                return ShowLinkResponseData::from(
                    $response
                );
            }
        });
    }

    public function update(string $reference, UpdateLinksRequestData $updateLinksRequestData, array $headers = []): UpdateLinksResponseData
    {
        return $this->handleExceptions(function () use ($reference, $updateLinksRequestData, $headers): UpdateLinksResponseData {
            return UpdateLinksResponseData::from(
                $this->linksClient->update(
                    $reference,
                    $updateLinksRequestData->items,
                    $headers
                )
            );
        });
    }
    public function showLinkGroup(string $reference, ShowLinksGroupRequestData $showLinksGroupRequest, array $headers = []): CreateLinksResponseData
    {
        return $this->handleExceptions(function () use ($reference, $showLinksGroupRequest, $headers): CreateLinksResponseData {
            return CreateLinksResponseData::fromArray(
                $this->linksClient->showLinkGroup($reference, $showLinksGroupRequest->toArray(), $headers)
            );
        });
    }

    public function cancel(string $reference, CancelLinksRequestData $cancelLinksRequestData, array $headers = []): CancelLinksResponseData
    {
        return $this->handleExceptions(function () use ($reference, $cancelLinksRequestData, $headers): CancelLinksResponseData {
            return CancelLinksResponseData::from(
                $this->linksClient->cancel(
                    $reference,
                    $cancelLinksRequestData->items,
                    $headers
                )
            );
        });
    }

    public function showBySerial(string $serial, array $headers = []): ShowLinkResponseData
    {
        return $this->handleExceptions(function () use ($serial, $headers): ShowLinkResponseData {
            return ShowLinkResponseData::from(
                $this->serialClient->show($serial, $headers)
            );
        });
    }

    public function redeemBySerial($serial, array $headers = []): RedeemLinkResponseData|ShowLinkResponseData
    {
        return $this->handleExceptions(function () use ($serial, $headers): RedeemLinkResponseData|ShowLinkResponseData {
            $response = $this->serialClient->redeem($serial, $headers);

            if (array_key_exists('status', $response)) {
                return RedeemLinkResponseData::from(
                    $response
                );
            } else {
                return ShowLinkResponseData::from(
                    $response
                );
            }
        });
    }

    public function updateBySerial(UpdateLinksBySerialRequestData $updateLinksBySerialData, array $headers = []): UpdateLinksResponseData
    {
        return $this->handleExceptions(function () use ($updateLinksBySerialData, $headers): UpdateLinksResponseData {
            return UpdateLinksResponseData::from(
                $this->serialClient->update(
                    $updateLinksBySerialData->items,
                    $headers
                )
            );
        });
    }

    public function cancelBySerial(CancelLinksRequestData $cancelLinksBySerialRequestData, array $headers = []): CancelLinksResponseData
    {
        return $this->handleExceptions(function () use ($cancelLinksBySerialRequestData, $headers): CancelLinksResponseData {
            return CancelLinksResponseData::from(
                $this->serialClient->cancel(
                    $cancelLinksBySerialRequestData->items,
                    $headers
                )
            );
        });
    }

    public function showByShortCode($shortCode, array $headers = []): ShowLinkResponseData
    {
        return $this->handleExceptions(function () use ($shortCode, $headers): ShowLinkResponseData {
            return ShowLinkResponseData::from(
                $this->shortCodeClient->show($shortCode, $headers)
            );
        });
    }

    public function redeemByShortCode($shortCode, array $headers = []): RedeemLinkResponseData|ShowLinkResponseData
    {
        return $this->handleExceptions(function () use ($shortCode, $headers): RedeemLinkResponseData|ShowLinkResponseData {
            $response = $this->shortCodeClient->redeem($shortCode, $headers);

            if (array_key_exists('status', $response)) {
                return RedeemLinkResponseData::from(
                    $response
                );
            } else {
                return ShowLinkResponseData::from(
                    $response
                );
            }
        });
    }

    public function updateByShortCode(UpdateLinksByShortCodeRequestData $updateLinkByShortCodeData, array $headers = []): UpdateLinksResponseData
    {
        return $this->handleExceptions(function () use ($updateLinkByShortCodeData, $headers): UpdateLinksResponseData {
            return UpdateLinksResponseData::from(
                $this->shortCodeClient->update(
                    $updateLinkByShortCodeData->items,
                    $headers
                )
            );
        });
    }

    public function cancelByShortCode(CancelLinksRequestData $cancelLinksByShortCodeData, array $headers = []): CancelLinksResponseData
    {
        return $this->handleExceptions(function () use ($cancelLinksByShortCodeData, $headers): CancelLinksResponseData {
            return CancelLinksResponseData::from(
                $this->shortCodeClient->cancel(
                    $cancelLinksByShortCodeData->items,
                    $headers
                )
            );
        });
    }

    public function updateMetadataForAsset(string $assetIdentifier, mixed $metadata, array $headers = []): array
    {
        return $this->handleExceptions(function () use ($assetIdentifier, $metadata, $headers): array {
            return $this->metadataClient->updateForAsset($assetIdentifier, $metadata, $headers);
        });
    }

    private function handleExceptions(callable $callback): mixed
    {
        try {
            return $callback();
        } catch (RequestException $exception) {
            $this->handleClientException($exception);
        } catch (AuthenticationException $exception) {
            $this->flushToken();
            throw $exception;
        }
    }
    private function handleClientException(RequestException $exception): never
    {
        $request = $exception->getRequest();
        $code = (int)$exception->getCode();
        $errorDetails = null;
        $response = null;

        if ($exception->hasResponse()) {
            $response = $exception->getResponse();
            $code = $response->getStatusCode();

            $body = $response->getBody()->getContents();
            $decoded = json_decode($body, true);

            if (
                json_last_error() === JSON_ERROR_NONE &&
                is_array($decoded) &&
                array_key_exists('status', $decoded)
            ) {
                $errorDetails = ErrorResponseData::from($decoded);
            }
        }

        throw new LinksModuleRequestException(
            errorDetails: $errorDetails,
            request: $request,
            response: $response,
            code: $code,
            previous: $exception
        );
    }

    private function flushToken(): void
    {
        Cache::forget(Config::get('link-module.cache_key'));
    }
}
