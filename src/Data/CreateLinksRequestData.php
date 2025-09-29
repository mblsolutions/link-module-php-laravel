<?php

namespace MBLSolutions\LinkModuleLaravel\Data;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class CreateLinksRequestData extends Data
{
    public function __construct(
        #[Required]
        public string $uuid,

        #[Required, WithCast(DateTimeInterfaceCast::class, type: Carbon::class)]
        public CarbonInterface $expiration,

        #[Required]
        public string $callback,

        #[Required]
        public int $quantity = 1,

        public mixed $meta = null,

        public mixed $asset_meta = null,

        public string $asset_identifier = '',

        public bool $short_code = false,

        public string $short_code_sku = ''
    ) {}
}
