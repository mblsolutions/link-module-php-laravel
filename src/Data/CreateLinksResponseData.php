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
class CreateLinksResponseData extends Data
{
    public function __construct(
        #[Required]
        public string $referenceUuid,

        #[Required, WithCast(DateTimeInterfaceCast::class, type: Carbon::class)]
        public CarbonInterface $expiration,

        /**
         * @var array<int, LinkData>
         */
        #[DataCollectionOf(LinkData::class)]
        public array $items,
    )
    {
    }
}