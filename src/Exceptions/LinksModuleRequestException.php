<?php

namespace MBLSolutions\LinkModuleLaravel\Exceptions;

use MBLSolutions\LinkModuleLaravel\Data\ErrorResponseData;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Throwable;

class LinksModuleRequestException extends RuntimeException
{
    public function __construct(
        public ?ErrorResponseData $errorDetails,
        public ?RequestInterface $request,
        public ?ResponseInterface $response = null,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $message = 'An error occurred with the Links Module API';

        if ($errorDetails) {
            $message = $errorDetails->message;
        } else {
            if ($response) {
                $response->getBody()->rewind();
                $message = 'Unexpected response: ' . $response->getBody()->getContents();
            }
        }

        parent::__construct($message, $code, $previous);
    }
}
