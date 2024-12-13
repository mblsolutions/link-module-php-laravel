<?php

namespace MBLSolutions\LinkModuleLaravel\Data;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class UpdateLinksRequestData extends Data
{
    public function __construct(
        /**
         * @var array<int, UpdateLinkData>
         */
        #[DataCollectionOf(UpdateLinkData::class)]
        public array $items,
    )
    {
    }
}