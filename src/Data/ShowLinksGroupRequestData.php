<?php

namespace MBLSolutions\LinkModuleLaravel\Data;

use Spatie\LaravelData\Data;

class ShowLinksGroupRequestData extends Data
{
    public function __construct(

        public bool $short_code = false,
    ) {}
}
