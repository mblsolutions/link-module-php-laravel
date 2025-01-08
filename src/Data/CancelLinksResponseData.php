<?php

namespace MBLSolutions\LinkModuleLaravel\Data;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class CancelLinksResponseData extends Data
{
    public function __construct(
        #[Required]
        public string $uuid,

        #[Required, WithCast(DateTimeInterfaceCast::class, type: Carbon::class)]
        public CarbonInterface $expiration,

        #[Required, WithCast(DateTimeInterfaceCast::class, type: Carbon::class)]
        public CarbonInterface $updatedAt,

        /**
         * @var array<int, CancelledLinkData>
         */
        #[DataCollectionOf(CancelledLinkData::class)]
        public array $items,
    )
    {
    }
}
