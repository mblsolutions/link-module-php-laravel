<?php

namespace MBLSolutions\LinkModuleLaravel\Cast;

use MBLSolutions\LinkModuleLaravel\LinkDecryptionService;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Creation\CreationContext;

class DecryptCast implements Cast
{

    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        if (is_null($value)) {
            return null;
        }

        if (!app()->bound(LinkDecryptionService::class)) {
            return $value;
        }

        $decryptor = app(LinkDecryptionService::class);

        return $decryptor->decrypt($value);
    }
}
