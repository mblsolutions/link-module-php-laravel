<?php

namespace MBLSolutions\LinkModuleLaravel\Data;

use Spatie\LaravelData\Data;

class ErrorResponseData extends Data
{
    public function __construct(
        public StatusEnum $status = StatusEnum::Error,

        public string $message = 'Unknown Error',
    )
    {
    }
}