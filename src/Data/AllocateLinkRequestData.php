<?php

namespace MBLSolutions\LinkModuleLaravel\Data;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class AllocateLinkRequestData extends Data
{
    public function __construct(
        #[Required]
        public string $uuid,

        #[Required, WithCast(DateTimeInterfaceCast::class, type: Carbon::class)]
        public CarbonInterface $expiration,

        #[Required]
        public string $callback,

        public mixed $meta = null,

        #[Required]
        public string $short_code,

        #[Required]
        public string $serial,

        #[Required]
        public string $short_code_sku = ''
    ) {}
}
