<?php

declare(strict_types=1);

namespace App\Tests\Domain\Service;

use App\Domain\Aquarium\Entity\Aquarium;
use App\Domain\Aquarium\ValueObject\AquariumId;
use App\Domain\Aquarium\ValueObject\TurnNumber;
use App\Domain\Fish\Entity\Fish;
use App\Domain\Fish\ValueObject\FishId;
use App\Domain\Fish\ValueObject\Sex;
use App\Domain\Fish\ValueObject\SexualBehaviorType;
use App\Domain\Fish\ValueObject\Species;
use App\Domain\Service\MonosexualReproductionStrategy;
use App\Domain\Service\RandomGeneratorInterface;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\ValueObject\HealthPoints;
use PHPUnit\Framework\TestCase;

final class MonosexualReproductionStrategyTest extends TestCase
{
    private MonosexualReproductionStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new MonosexualReproductionStrategy();
    }

    public function test_supports_monosexual_behavior(): void
    {
        $this->assertTrue($this->strategy->supports(SexualBehaviorType::MONOSEXUAL));
        $this->assertFalse($this->strategy->supports(SexualBehaviorType::PROTANDROUS));
        $this->assertFalse($this->strategy->supports(SexualBehaviorType::OPPORTUNISTIC));
    }

    public function test_updateSex_does_not_change_sex(): void
    {
        $fish = $this->createFish(Sex::MALE, 5);

        $this->strategy->updateSex($fish);

        $this->assertSame(Sex::MALE, $fish->getSex());
    }

    public function test_findValidPartners_returns_opposite_sex_same_species(): void
    {
        $aquarium = $this->createAquarium();
        $maleCarp = $this->createFish(Sex::MALE, 3, Species::CARP);
        $femaleCarp = $this->createFish(Sex::FEMALE, 3, Species::CARP);
        $maleTuna = $this->createFish(Sex::MALE, 3, Species::TUNA);

        $aquarium->addFish($maleCarp);
        $aquarium->addFish($femaleCarp);
        $aquarium->addFish($maleTuna);

        $partners = $this->strategy->findValidPartners($aquarium, $maleCarp);

        $this->assertCount(1, $partners);
        $this->assertTrue($partners[0]->getId()->equals($femaleCarp->getId()));
    }

    public function test_findValidPartners_excludes_same_sex(): void
    {
        $aquarium = $this->createAquarium();
        $maleCarp1 = $this->createFish(Sex::MALE, 3, Species::CARP);
        $maleCarp2 = $this->createFish(Sex::MALE, 3, Species::CARP);

        $aquarium->addFish($maleCarp1);
        $aquarium->addFish($maleCarp2);

        $partners = $this->strategy->findValidPartners($aquarium, $maleCarp1);

        $this->assertCount(0, $partners);
    }

    public function test_findValidPartners_excludes_different_species(): void
    {
        $aquarium = $this->createAquarium();
        $maleCarp = $this->createFish(Sex::MALE, 3, Species::CARP);
        $femaleTuna = $this->createFish(Sex::FEMALE, 3, Species::TUNA);

        $aquarium->addFish($maleCarp);
        $aquarium->addFish($femaleTuna);

        $partners = $this->strategy->findValidPartners($aquarium, $maleCarp);

        $this->assertCount(0, $partners);
    }

    public function test_findValidPartners_excludes_too_young_fish(): void
    {
        $aquarium = $this->createAquarium();
        $maleCarp = $this->createFish(Sex::MALE, 3, Species::CARP);
        $femaleCarp = $this->createFish(Sex::FEMALE, 1, Species::CARP);

        $aquarium->addFish($maleCarp);
        $aquarium->addFish($femaleCarp);

        $partners = $this->strategy->findValidPartners($aquarium, $maleCarp);

        $this->assertCount(0, $partners);
    }

    public function test_findValidPartners_excludes_dead_fish(): void
    {
        $aquarium = $this->createAquarium();
        $maleCarp = $this->createFish(Sex::MALE, 3, Species::CARP);
        $femaleCarp = $this->createFish(Sex::FEMALE, 3, Species::CARP);
        $femaleCarp->loseHealth(10); // Kill the fish

        $aquarium->addFish($maleCarp);
        $aquarium->addFish($femaleCarp);

        $partners = $this->strategy->findValidPartners($aquarium, $maleCarp);

        $this->assertCount(0, $partners);
    }

    public function test_findValidPartners_excludes_self(): void
    {
        $aquarium = $this->createAquarium();
        $maleCarp = $this->createFish(Sex::MALE, 3, Species::CARP);

        $aquarium->addFish($maleCarp);

        $partners = $this->strategy->findValidPartners($aquarium, $maleCarp);

        $this->assertCount(0, $partners);
    }

    public function test_reproduce_creates_offspring_with_random_sex(): void
    {
        $maleCarp = $this->createFish(Sex::MALE, 5, Species::CARP);
        $femaleCarp = $this->createFish(Sex::FEMALE, 5, Species::CARP);

        $randomGenerator = $this->createMock(RandomGeneratorInterface::class);
        $randomGenerator->method('randomBoolean')->willReturn(true); // Male

        $offspring = $this->strategy->reproduce($maleCarp, $femaleCarp, $randomGenerator);

        $this->assertSame(Species::CARP, $offspring->getSpecies());
        $this->assertSame(Sex::MALE, $offspring->getSex());
        $this->assertSame(0, $offspring->getAge()->toInt());
        $this->assertSame(10, $offspring->getHealthPoints()->toInt());
    }

    private function createFish(Sex $sex, int $age, Species $species = Species::CARP): Fish
    {
        return new Fish(
            id: FishId::generate(),
            name: new EntityName('Test Fish'),
            species: $species,
            sex: $sex,
            age: new Age($age),
            healthPoints: new HealthPoints(10)
        );
    }

    private function createAquarium(): Aquarium
    {
        return new Aquarium(
            id: AquariumId::generate(),
            name: new EntityName('Test Aquarium'),
            turnNumber: TurnNumber::initial()
        );
    }
}
