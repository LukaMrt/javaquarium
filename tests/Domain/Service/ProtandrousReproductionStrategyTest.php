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
use App\Domain\Service\ProtandrousReproductionStrategy;
use App\Domain\Service\RandomGeneratorInterface;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\ValueObject\HealthPoints;
use PHPUnit\Framework\TestCase;

final class ProtandrousReproductionStrategyTest extends TestCase
{
    private ProtandrousReproductionStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new ProtandrousReproductionStrategy();
    }

    public function test_supports_protandrous_behavior(): void
    {
        $this->assertTrue($this->strategy->supports(SexualBehaviorType::PROTANDROUS));
        $this->assertFalse($this->strategy->supports(SexualBehaviorType::MONOSEXUAL));
        $this->assertFalse($this->strategy->supports(SexualBehaviorType::OPPORTUNISTIC));
    }

    public function test_updateSex_sets_male_when_age_under_10(): void
    {
        $fish = $this->createFish(Sex::FEMALE, 5, Species::BASS);

        $this->strategy->updateSex($fish);

        $this->assertSame(Sex::MALE, $fish->getSex());
    }

    public function test_updateSex_sets_male_when_age_exactly_10(): void
    {
        $fish = $this->createFish(Sex::FEMALE, 10, Species::BASS);

        $this->strategy->updateSex($fish);

        $this->assertSame(Sex::MALE, $fish->getSex());
    }

    public function test_updateSex_sets_female_when_age_over_10(): void
    {
        $fish = $this->createFish(Sex::MALE, 11, Species::BASS);

        $this->strategy->updateSex($fish);

        $this->assertSame(Sex::FEMALE, $fish->getSex());
    }

    public function test_findValidPartners_returns_opposite_sex_after_age_based_update(): void
    {
        $aquarium = $this->createAquarium();
        $youngBass = $this->createFish(Sex::MALE, 5, Species::BASS); // Will be MALE (age <= 10)
        $oldBass = $this->createFish(Sex::MALE, 15, Species::BASS); // Will become FEMALE (age > 10)

        $aquarium->addFish($youngBass);
        $aquarium->addFish($oldBass);

        $partners = $this->strategy->findValidPartners($aquarium, $youngBass);

        $this->assertCount(1, $partners);
        $this->assertTrue($partners[0]->getId()->equals($oldBass->getId()));
        $this->assertSame(Sex::FEMALE, $oldBass->getSex()); // Sex was updated
    }

    public function test_findValidPartners_excludes_same_age_group(): void
    {
        $aquarium = $this->createAquarium();
        $youngBass1 = $this->createFish(Sex::MALE, 5, Species::BASS); // Will be MALE
        $youngBass2 = $this->createFish(Sex::FEMALE, 7, Species::BASS); // Will be MALE

        $aquarium->addFish($youngBass1);
        $aquarium->addFish($youngBass2);

        $partners = $this->strategy->findValidPartners($aquarium, $youngBass1);

        $this->assertCount(0, $partners); // Both are MALE after update
    }

    public function test_findValidPartners_excludes_different_species(): void
    {
        $aquarium = $this->createAquarium();
        $youngBass = $this->createFish(Sex::MALE, 5, Species::BASS);
        $oldGrouper = $this->createFish(Sex::MALE, 15, Species::GROUPER);

        $aquarium->addFish($youngBass);
        $aquarium->addFish($oldGrouper);

        $partners = $this->strategy->findValidPartners($aquarium, $youngBass);

        $this->assertCount(0, $partners);
    }

    public function test_findValidPartners_excludes_too_young_fish(): void
    {
        $aquarium = $this->createAquarium();
        $youngBass = $this->createFish(Sex::MALE, 5, Species::BASS);
        $baby = $this->createFish(Sex::FEMALE, 1, Species::BASS);

        $aquarium->addFish($youngBass);
        $aquarium->addFish($baby);

        $partners = $this->strategy->findValidPartners($aquarium, $youngBass);

        $this->assertCount(0, $partners);
    }

    public function test_findValidPartners_excludes_dead_fish(): void
    {
        $aquarium = $this->createAquarium();
        $youngBass = $this->createFish(Sex::MALE, 5, Species::BASS);
        $oldBass = $this->createFish(Sex::MALE, 15, Species::BASS);
        $oldBass->loseHealth(10); // Kill the fish

        $aquarium->addFish($youngBass);
        $aquarium->addFish($oldBass);

        $partners = $this->strategy->findValidPartners($aquarium, $youngBass);

        $this->assertCount(0, $partners);
    }

    public function test_findValidPartners_excludes_self(): void
    {
        $aquarium = $this->createAquarium();
        $bass = $this->createFish(Sex::MALE, 5, Species::BASS);

        $aquarium->addFish($bass);

        $partners = $this->strategy->findValidPartners($aquarium, $bass);

        $this->assertCount(0, $partners);
    }

    public function test_reproduce_creates_offspring_with_random_sex(): void
    {
        $youngBass = $this->createFish(Sex::MALE, 5, Species::BASS);
        $oldBass = $this->createFish(Sex::FEMALE, 15, Species::BASS);

        $randomGenerator = $this->createMock(RandomGeneratorInterface::class);
        $randomGenerator->method('randomBoolean')->willReturn(false); // Female

        $offspring = $this->strategy->reproduce($youngBass, $oldBass, $randomGenerator);

        $this->assertSame(Species::BASS, $offspring->getSpecies());
        $this->assertSame(Sex::FEMALE, $offspring->getSex());
        $this->assertSame(0, $offspring->getAge()->toInt());
        $this->assertSame(10, $offspring->getHealthPoints()->toInt());
    }

    private function createFish(Sex $sex, int $age, Species $species): Fish
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
