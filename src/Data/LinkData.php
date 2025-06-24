<?php

namespace MBLSolutions\LinkModuleLaravel\Data;

use MBLSolutions\LinkModuleLaravel\Cast\DecryptCast;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class LinkData extends Data
{
    public function __construct(
        #[Required]
        public string $uuid,

        #[Required]
        public string $link,

        public string $serial = '',

        #[WithCast(DecryptCast::class)]
        public string|null $decrypted_short_code = null,

        public string|null $short_code = null

    ) {}
}
