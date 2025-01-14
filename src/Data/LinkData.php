<?php

namespace MBLSolutions\LinkModuleLaravel\Data;

use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class LinkData extends Data
{
    public function __construct(
        #[Required]
        public string $uuid,

        #[Required]
        public string $link,

    ) {}
}
