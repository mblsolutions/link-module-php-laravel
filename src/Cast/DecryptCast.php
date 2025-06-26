<?php

namespace MBLSolutions\LinkModuleLaravel\Cast;

use MBLSolutions\LinkModuleLaravel\LinkDecryptionService;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Creation\CreationContext;

class DecryptCast implements Cast
{

    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): null|string
    {
        if (is_null($value) || !app()->bound(LinkDecryptionService::class)) {
            return null;
        }

        $decryptor = app(LinkDecryptionService::class);

        return $decryptor->decrypt($value);
    }
}
