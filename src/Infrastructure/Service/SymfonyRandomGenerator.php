<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Domain\Service\RandomGeneratorInterface;

final readonly class SymfonyRandomGenerator implements RandomGeneratorInterface
{
    public function selectRandom(array $items): mixed
    {
        if ($items === []) {
            throw new \InvalidArgumentException('Cannot select from empty array');
        }

        $randomKey = array_rand($items);
        return $items[$randomKey];
    }

    public function randomBoolean(): bool
    {
        return (bool) random_int(0, 1);
    }

    public function shuffle(array $items): array
    {
        $shuffled = $items;
        shuffle($shuffled);
        return $shuffled;
    }
}
