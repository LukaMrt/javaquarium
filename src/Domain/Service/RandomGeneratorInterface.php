<?php

declare(strict_types=1);

namespace App\Domain\Service;

interface RandomGeneratorInterface
{
    /**
     * Selects a random element from the given array.
     *
     * @template T
     * @param array<T> $items
     * @return T
     * @throws \InvalidArgumentException if array is empty
     */
    public function selectRandom(array $items): mixed;

    /**
     * Returns a random boolean value.
     */
    public function randomBoolean(): bool;
}
