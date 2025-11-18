<?php

declare(strict_types=1);

namespace App\Domain\Aquarium\Repository;

use App\Domain\Aquarium\Entity\Aquarium;
use App\Domain\Aquarium\ValueObject\AquariumId;

interface AquariumRepositoryInterface
{
    public function save(Aquarium $aquarium): void;

    public function findById(AquariumId $id): ?Aquarium;

    /**
     * @return Aquarium[]
     */
    public function findAllAquariums(): array;

    public function delete(Aquarium $aquarium): void;
}
