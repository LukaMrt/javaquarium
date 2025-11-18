<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Aquarium\Entity\Aquarium;
use App\Domain\Aquarium\Repository\AquariumRepositoryInterface;
use App\Domain\Aquarium\ValueObject\AquariumId;
use App\Infrastructure\Persistence\Doctrine\Entity\AquariumEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

/**
 * @extends ServiceEntityRepository<AquariumEntity>
 */
final class DoctrineAquariumRepository extends ServiceEntityRepository implements AquariumRepositoryInterface
{
    public function __construct(
        private readonly ObjectMapperInterface $objectMapper,
        ManagerRegistry $registry
    ) {
        parent::__construct($registry, AquariumEntity::class);
    }

    public function save(Aquarium $aquarium): void
    {
        $existingEntity = $this->find($aquarium->getId());

        $entity = $this->objectMapper->map($aquarium, $existingEntity ?? AquariumEntity::class);

        if ($existingEntity === null) {
            $this->getEntityManager()->persist($entity);
        }

        $this->getEntityManager()->flush();
    }

    public function findById(AquariumId $id): ?Aquarium
    {
        $entity = $this->find($id);
        if ($entity === null) {
            return null;
        }

        return $this->objectMapper->map($entity, Aquarium::class);
    }

    /**
     * @return Aquarium[]
     */
    public function findAllAquariums(): array
    {
        return array_map(
            fn (AquariumEntity $entity): Aquarium => $this->objectMapper->map($entity, Aquarium::class),
            parent::findAll()
        );
    }

    public function delete(Aquarium $aquarium): void
    {
        $entity = $this->find($aquarium->getId());
        if ($entity === null) {
            return;
        }

        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}
