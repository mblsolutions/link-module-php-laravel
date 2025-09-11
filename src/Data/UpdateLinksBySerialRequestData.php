<?php

namespace MBLSolutions\LinkModuleLaravel\Data;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

class UpdateLinksBySerialRequestData extends Data
{
    public function __construct(
        /**
         * @var array<int, UpdateLinkBySerialData>
         */
        #[DataCollectionOf(UpdateLinkBySerialData::class)]
        public array $items,
    ) {}
}
