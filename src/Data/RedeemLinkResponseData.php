<?php

namespace MBLSolutions\LinkModuleLaravel\Data;

use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class RedeemLinkResponseData extends Data
{
    public function __construct(
        #[Required]
        public StatusEnum $status,
    )
    {
    }
}