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
use App\Domain\Service\OpportunisticReproductionStrategy;
use App\Domain\Service\RandomGeneratorInterface;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\ValueObject\HealthPoints;
use PHPUnit\Framework\TestCase;

final class OpportunisticReproductionStrategyTest extends TestCase
{
    private OpportunisticReproductionStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new OpportunisticReproductionStrategy();
    }

    public function test_supports_opportunistic_behavior(): void
    {
        $this->assertTrue($this->strategy->supports(SexualBehaviorType::OPPORTUNISTIC));
        $this->assertFalse($this->strategy->supports(SexualBehaviorType::MONOSEXUAL));
        $this->assertFalse($this->strategy->supports(SexualBehaviorType::PROTANDROUS));
    }

    public function test_updateSex_does_not_change_sex_without_partner(): void
    {
        $fish = $this->createFish(Sex::MALE, 5);

        $this->strategy->updateSex($fish);

        $this->assertSame(Sex::MALE, $fish->getSex());
    }

    public function test_updateSex_changes_to_female_when_partner_is_male(): void
    {
        $fish = $this->createFish(Sex::MALE, 5);
        $partner = $this->createFish(Sex::MALE, 5);

        $this->strategy->updateSex($fish, $partner);

        $this->assertSame(Sex::FEMALE, $fish->getSex());
    }

    public function test_updateSex_changes_to_male_when_partner_is_female(): void
    {
        $fish = $this->createFish(Sex::FEMALE, 5);
        $partner = $this->createFish(Sex::FEMALE, 5);

        $this->strategy->updateSex($fish, $partner);

        $this->assertSame(Sex::MALE, $fish->getSex());
    }

    public function test_updateSex_keeps_sex_when_already_opposite(): void
    {
        $fish = $this->createFish(Sex::MALE, 5);
        $partner = $this->createFish(Sex::FEMALE, 5);

        $this->strategy->updateSex($fish, $partner);

        $this->assertSame(Sex::MALE, $fish->getSex());
    }

    public function test_findValidPartners_returns_any_same_species(): void
    {
        $aquarium = $this->createAquarium();
        $maleSole = $this->createFish(Sex::MALE, 3, Species::SOLE);
        $maleSole2 = $this->createFish(Sex::MALE, 3, Species::SOLE);
        $femaleSole = $this->createFish(Sex::FEMALE, 3, Species::SOLE);

        $aquarium->addFish($maleSole);
        $aquarium->addFish($maleSole2);
        $aquarium->addFish($femaleSole);

        $partners = $this->strategy->findValidPartners($aquarium, $maleSole);

        // Opportunistic can mate with any same species (will adapt)
        $this->assertCount(2, $partners);
    }

    public function test_findValidPartners_excludes_different_species(): void
    {
        $aquarium = $this->createAquarium();
        $maleSole = $this->createFish(Sex::MALE, 3, Species::SOLE);
        $maleClownfish = $this->createFish(Sex::MALE, 3, Species::CLOWNFISH);

        $aquarium->addFish($maleSole);
        $aquarium->addFish($maleClownfish);

        $partners = $this->strategy->findValidPartners($aquarium, $maleSole);

        $this->assertCount(0, $partners);
    }

    public function test_findValidPartners_excludes_too_young_fish(): void
    {
        $aquarium = $this->createAquarium();
        $maleSole = $this->createFish(Sex::MALE, 3, Species::SOLE);
        $baby = $this->createFish(Sex::FEMALE, 1, Species::SOLE);

        $aquarium->addFish($maleSole);
        $aquarium->addFish($baby);

        $partners = $this->strategy->findValidPartners($aquarium, $maleSole);

        $this->assertCount(0, $partners);
    }

    public function test_findValidPartners_excludes_dead_fish(): void
    {
        $aquarium = $this->createAquarium();
        $maleSole = $this->createFish(Sex::MALE, 3, Species::SOLE);
        $femaleSole = $this->createFish(Sex::FEMALE, 3, Species::SOLE);
        $femaleSole->loseHealth(10); // Kill the fish

        $aquarium->addFish($maleSole);
        $aquarium->addFish($femaleSole);

        $partners = $this->strategy->findValidPartners($aquarium, $maleSole);

        $this->assertCount(0, $partners);
    }

    public function test_findValidPartners_excludes_self(): void
    {
        $aquarium = $this->createAquarium();
        $sole = $this->createFish(Sex::MALE, 3, Species::SOLE);

        $aquarium->addFish($sole);

        $partners = $this->strategy->findValidPartners($aquarium, $sole);

        $this->assertCount(0, $partners);
    }

    public function test_reproduce_creates_offspring_with_random_sex(): void
    {
        $maleSole = $this->createFish(Sex::MALE, 5, Species::SOLE);
        $femaleSole = $this->createFish(Sex::FEMALE, 5, Species::SOLE);

        $randomGenerator = $this->createMock(RandomGeneratorInterface::class);
        $randomGenerator->method('randomBoolean')->willReturn(true); // Male

        $offspring = $this->strategy->reproduce($maleSole, $femaleSole, $randomGenerator);

        $this->assertSame(Species::SOLE, $offspring->getSpecies());
        $this->assertSame(Sex::MALE, $offspring->getSex());
        $this->assertSame(0, $offspring->getAge()->toInt());
        $this->assertSame(10, $offspring->getHealthPoints()->toInt());
    }

    private function createFish(Sex $sex, int $age, Species $species = Species::SOLE): Fish
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
