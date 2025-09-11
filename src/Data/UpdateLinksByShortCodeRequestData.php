<?php

namespace MBLSolutions\LinkModuleLaravel\Data;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

class UpdateLinksByShortCodeRequestData extends Data
{
    public function __construct(
        /**
         * @var array<int, UpdateLinkByShortCodeData>
         */
        #[DataCollectionOf(UpdateLinkByShortCodeData::class)]
        public array $items,
    ) {}
}
