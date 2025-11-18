<?php

declare(strict_types=1);

namespace App\Application\UseCase\Algae\AddAlgaeToAquarium;

use App\Domain\Algae\Entity\Algae;
use App\Lib\Mapper\ToStringTransformer;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(source: Algae::class)]
final readonly class AddAlgaeResponse
{
    public function __construct(
        #[Map(source: 'id', transform: ToStringTransformer::class)]
        public string $algaeId
    ) {
    }
}
