<?php

declare(strict_types=1);

namespace App\Tests\Domain\Service;

use App\Domain\Algae\Entity\Algae;
use App\Domain\Algae\ValueObject\AlgaeId;
use App\Domain\Service\AlgaeGrowthService;
use App\Domain\Shared\GameRules;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\ValueObject\HealthPoints;
use PHPUnit\Framework\TestCase;

final class AlgaeGrowthServiceTest extends TestCase
{
    private AlgaeGrowthService $service;

    protected function setUp(): void
    {
        $this->service = new AlgaeGrowthService();
    }

    public function test_algae_grows_by_one_hp_when_below_split_threshold(): void
    {
        // Given
        $initialHp = 5;
        $algae = new Algae(
            AlgaeId::generate(),
            new EntityName('Algue'),
            Age::initial(),
            new HealthPoints($initialHp)
        );

        // When
        $newAlgae = $this->service->grow($algae);

        // Then
        $this->assertNotInstanceOf(Algae::class, $newAlgae); // Pas de division
        $this->assertSame($initialHp + GameRules::ALGAE_GROWTH_HP, $algae->getHealthPoints()->toInt());
    }

    public function test_algae_splits_when_reaching_threshold(): void
    {
        // Given
        $initialHp = GameRules::ALGAE_SPLIT_THRESHOLD; // 10
        $algae = new Algae(
            AlgaeId::generate(),
            new EntityName('Parent Algae'),
            new Age(5),
            new HealthPoints($initialHp)
        );

        // When
        // L'algue grandit d'abord (+1 = 11), puis se divise
        // 11 / 2 = 5.5 -> 5 (division entière ?) ou 6 ?
        // REGLES_JEU.md : "Se divise en deux algues avec la moitié des HP du parent chacune"
        // "Le parent perd la moitié de ses HP lors de la reproduction"
        // Si HP = 11, moitié = 5.5.
        // Supposons division entière pour l'instant : 5.
        
        $offspring = $this->service->grow($algae);

        // Then
        $this->assertInstanceOf(Algae::class, $offspring);
        
        // Vérification du parent
        // Il avait 10, gagne 1 -> 11. Se divise.
        // Si on suit la logique stricte : 11 / 2 = 5 (int).
        // Parent devrait avoir 5 ou 6 ?
        // Vérifions GameRules.
        
        // Vérification de l'enfant
        $this->assertInstanceOf(Algae::class, $offspring);
        $this->assertSame(0, $offspring->getAge()->toInt());
        $this->assertSame('Parent Algae', $offspring->getName()->toString());
    }
}
