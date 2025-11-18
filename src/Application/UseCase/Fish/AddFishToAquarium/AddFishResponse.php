<?php

declare(strict_types=1);

namespace App\Application\UseCase\Fish\AddFishToAquarium;

use App\Domain\Fish\Entity\Fish;
use App\Lib\Mapper\ToStringTransformer;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(source: Fish::class)]
final readonly class AddFishResponse
{
    public function __construct(
        #[Map(source: 'id', transform: ToStringTransformer::class)]
        public string $fishId
    ) {
    }
}
