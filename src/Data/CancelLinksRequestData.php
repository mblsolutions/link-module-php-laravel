<?php

namespace MBLSolutions\LinkModuleLaravel\Data;

use Spatie\LaravelData\Data;

class CancelLinksRequestData extends Data
{
    public function __construct(
        /**
         * @var array<int, string>
         */
        public array $items,
    )
    {
    }
}
