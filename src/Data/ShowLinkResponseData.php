<?php

namespace MBLSolutions\LinkModuleLaravel\Data;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class ShowLinkResponseData extends Data
{
    public function __construct(
        #[Required]
        public string $uuid,

        #[Required]
        public string|array|null|bool $value = null,

        #[WithCast(DateTimeInterfaceCast::class, type: Carbon::class)]
        public ?CarbonInterface $expiration = null,

        #[Required]
        public string|null $template = null,

        #[Required]
        public mixed $meta = null
    ) {}
}
