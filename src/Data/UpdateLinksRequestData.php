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
    ) {}

    public function toArray(): array
    {
        $data = parent::toArray();
        return collect($data['items'])->map(function ($item) {
            if ($item['expiration'] == null) {
                unset($item['expiration']);
            }
            return $item;
        })->toArray();
    }
}
