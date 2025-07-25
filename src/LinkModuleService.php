<?php

namespace MBLSolutions\LinkModuleLaravel;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use MBLSolutions\LinkModule\Exceptions\AuthenticationException;
use MBLSolutions\LinkModule\Links as LinksClient;
use MBLSolutions\LinkModuleLaravel\Data\CreateLinksResponseData;
use MBLSolutions\LinkModuleLaravel\Data\CreateLinksRequestData;
use MBLSolutions\LinkModuleLaravel\Data\CancelLinksResponseData;
use MBLSolutions\LinkModuleLaravel\Data\CancelLinksRequestData;
use MBLSolutions\LinkModuleLaravel\Data\ErrorResponseData;
use MBLSolutions\LinkModuleLaravel\Data\RedeemLinkResponseData;
use MBLSolutions\LinkModuleLaravel\Data\ShowLinkResponseData;
use MBLSolutions\LinkModuleLaravel\Data\ShowLinksGroupRequestData;
use MBLSolutions\LinkModuleLaravel\Data\UpdateLinksRequestData;
use MBLSolutions\LinkModuleLaravel\Data\UpdateLinksResponseData;
use MBLSolutions\LinkModuleLaravel\Exceptions\LinksModuleRequestException;

class LinkModuleService
{
    public function __construct(
        private LinksClient $linksClient
    ) {}

    public function create(CreateLinksRequestData $createLinksRequest, array $headers = []): CreateLinksResponseData
    {
        try {
            return CreateLinksResponseData::fromArray(
                $this->linksClient->create(
                    $createLinksRequest->toArray(),
                    $headers
                )
            );
        } catch (RequestException $exception) {
            $this->handleClientException($exception);
        } catch (AuthenticationException $exception) {
            $this->flushToken();

            throw $exception;
        }
    }

    public function show(string $reference, string $item, array $headers = []): ShowLinkResponseData
    {
        try {
            return ShowLinkResponseData::from(
                $this->linksClient->show($reference, $item, $headers)
            );
        } catch (RequestException $exception) {
            $this->handleClientException($exception);
        } catch (AuthenticationException $exception) {
            $this->flushToken();

            throw $exception;
        }
    }

    public function redeem(string $reference, string $item, $headers = []): RedeemLinkResponseData|ShowLinkResponseData
    {
        try {
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
        } catch (RequestException $exception) {
            $this->handleClientException($exception);
        } catch (AuthenticationException $exception) {
            $this->flushToken();

            throw $exception;
        }
    }

    public function update(string $reference, UpdateLinksRequestData $updateLinksRequestData, $headers = [])
    {
        try {
            return UpdateLinksResponseData::from(
                $this->linksClient->update(
                    $reference,
                    $updateLinksRequestData->items,
                    $headers
                )
            );
        } catch (RequestException $exception) {
            $this->handleClientException($exception);
        } catch (AuthenticationException $exception) {
            $this->flushToken();

            throw $exception;
        }
    }
    public function showLinkGroup(string $reference, ShowLinksGroupRequestData $showLinksGroupRequest, array $headers = []): CreateLinksResponseData
    {
        try {
            return CreateLinksResponseData::fromArray(
                $this->linksClient->showLinkGroup($reference, $showLinksGroupRequest->toArray(), $headers)
            );
        } catch (RequestException $exception) {
            $this->handleClientException($exception);
        } catch (AuthenticationException $exception) {
            $this->flushToken();

            throw $exception;
        }
    }

    public function cancel(string $reference, CancelLinksRequestData $cancelLinksRequestData, $headers = [])
    {
        try {
            return CancelLinksResponseData::from(
                $this->linksClient->cancel(
                    $reference,
                    $cancelLinksRequestData->items,
                    $headers
                )
            );
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
