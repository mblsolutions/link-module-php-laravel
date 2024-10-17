<?php

namespace MBLSolutions\LinkModuleLaravel;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use MBLSolutions\LinkModule\Exceptions\AuthenticationException;
use MBLSolutions\LinkModule\Links as LinksClient;
use MBLSolutions\LinkModuleLaravel\Data\CreateLinksResponseData;
use MBLSolutions\LinkModuleLaravel\Data\CreateLinksRequestData;
use MBLSolutions\LinkModuleLaravel\Data\ErrorResponseData;
use MBLSolutions\LinkModuleLaravel\Data\LinkData;
use MBLSolutions\LinkModuleLaravel\Data\RedeemLinkResponseData;
use MBLSolutions\LinkModuleLaravel\Exceptions\LinksModuleRequestException;

class LinkModuleService
{
    public function __construct(
        private LinksClient $linksClient
    )
    {
    }

    public function create(CreateLinksRequestData $createLinksRequest): CreateLinksResponseData
    {
        try {
            return CreateLinksResponseData::from(
                $this->linksClient->create(
                    $createLinksRequest->toArray()
                )
            );
        } catch (RequestException $exception) {
            $this->handleClientException($exception);
        } catch (AuthenticationException $exception) {
            $this->flushToken();

            throw $exception;
        }
    }

    public function show(string $reference, string $item): LinkData
    {
        try {
            return LinkData::from(
                $this->linksClient->show($reference, $item)
            );
        } catch (RequestException $exception) {
            $this->handleClientException($exception);
        } catch (AuthenticationException $exception) {
            $this->flushToken();

            throw $exception;
        }
    }

    public function redeem(string $reference, string $item): RedeemLinkResponseData
    {
        try {
            return RedeemLinkResponseData::from(
                $this->linksClient->redeem($reference, $item)
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

        if ($exception->hasResponse()) {
            $response = $exception->getResponse();
            $code = $response->getStatusCode();

            $body = $response->getBody()->getContents();
            $decoded = json_decode($body, true);

            if (json_last_error() === JSON_ERROR_NONE &&
                is_array($decoded) &&
                array_key_exists('status', $decoded)) {
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