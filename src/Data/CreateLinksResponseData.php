<?php

namespace MBLSolutions\LinkModuleLaravel\Data;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use MBLSolutions\LinkModuleLaravel\LinkDecryptionService;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class CreateLinksResponseData extends Data
{
    public function __construct(
        #[Required]
        public string $referenceUuid,

        #[Required, WithCast(DateTimeInterfaceCast::class, type: Carbon::class)]
        public CarbonInterface $expiration,

        /**
         * @var array<int, LinkData>
         */
        #[DataCollectionOf(LinkData::class)]
        public array $items,
    ) {}

    public static function fromArray(array $data): self
    {
        $link = (isset($data["link"])) ? str_replace("{reference_uuid}", $data["reference_uuid"], $data["link"]) : '';
        $linkDecryptionService = app()->bound(LinkDecryptionService::class) ? app(LinkDecryptionService::class) : null;

        return new self(
            referenceUuid: $data['reference_uuid'],
            expiration: Carbon::parse($data['expiration']),
            items: array_map(
                function ($item) use ($link, $linkDecryptionService) {
                    if (is_array($item)) {
                        $link = (isset($item['link'])) ? $item['link'] : str_replace("{item_uuid}", $item['uuid'], $link);
                        $serial = (isset($item['serial'])) ? $item['serial'] : '';
                        $short_code = (isset($item['short_code'])) ? $item['short_code'] : '';
                        return new LinkData(uuid: $item['uuid'], link: $link, serial: $serial, decrypted_short_code: CreateLinksResponseData::decryptShortCode($short_code, $linkDecryptionService), short_code: $short_code);
                    } else {
                        $link = str_replace("{item_uuid}", $item, $link);
                        return new LinkData(uuid: $item, link: $link);
                    }
                },
                $data['items']
            )
        );
    }

    private static function decryptShortCode(string|null $value, LinkDecryptionService|null $linkDecryptionService): string|null
    {
        if ($linkDecryptionService === null || $value === null || $value === '') {
            return null;
        }

        return $linkDecryptionService->decrypt($value);
    }
}
