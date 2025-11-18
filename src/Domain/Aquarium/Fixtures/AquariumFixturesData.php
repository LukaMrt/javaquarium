<?php

declare(strict_types=1);

namespace App\Domain\Aquarium\Fixtures;

use App\Domain\Algae\Entity\Algae;
use App\Domain\Algae\ValueObject\AlgaeId;
use App\Domain\Aquarium\Entity\Aquarium;
use App\Domain\Aquarium\ValueObject\AquariumId;
use App\Domain\Aquarium\ValueObject\TurnNumber;
use App\Domain\Fish\Entity\Fish;
use App\Domain\Fish\ValueObject\FishId;
use App\Domain\Fish\ValueObject\Sex;
use App\Domain\Fish\ValueObject\Species;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\ValueObject\HealthPoints;

final class AquariumFixturesData
{
    public const string TROPICAL_REEF = 'TROPICAL_REEF';

    public const string CARP_POND = 'CARP_POND';

    public const string BASS_POOL = 'BASS_POOL';

    public const string GROUPER_TANK = 'GROUPER_TANK';

    public const string SOLE_ZONE = 'SOLE_ZONE';

    public static function tropicalReef(): Aquarium
    {
        $aquarium = new Aquarium(
            id: AquariumId::fromString('4edea78b-1f08-4cc2-9d42-4f84aae9b79e'),
            name: new EntityName('Récif Tropical'),
            turnNumber: new TurnNumber(5)
        );

        $aquarium->addFish(new Fish(
            id: FishId::fromString('c0cc5c65-bb78-4b12-8fe9-b03376bcf186'),
            name: new EntityName('Nemo'),
            species: Species::CLOWNFISH,
            sex: Sex::MALE,
            age: new Age(2),
            healthPoints: new HealthPoints(8)
        ));

        $aquarium->addFish(new Fish(
            id: FishId::fromString('2430cce8-106e-4717-9a1d-ef52baaf4a68'),
            name: new EntityName('Dory'),
            species: Species::TUNA,
            sex: Sex::FEMALE,
            age: new Age(3),
            healthPoints: new HealthPoints(7)
        ));

        $aquarium->addFish(new Fish(
            id: FishId::fromString('163ab945-9dc9-4796-be7d-9215a6b99212'),
            name: new EntityName('Marlin'),
            species: Species::CLOWNFISH,
            sex: Sex::MALE,
            age: new Age(4),
            healthPoints: new HealthPoints(9)
        ));

        $aquarium->addAlgae(new Algae(
            id: AlgaeId::fromString('888b737c-e70c-4a45-9119-4bf6a0b89b91'),
            name: new EntityName('Algue Verte'),
            age: new Age(1),
            healthPoints: new HealthPoints(5)
        ));

        $aquarium->addAlgae(new Algae(
            id: AlgaeId::fromString('2febb902-117e-4ca9-96cf-9f0456f3c045'),
            name: new EntityName('Algue Brune'),
            age: new Age(2),
            healthPoints: new HealthPoints(6)
        ));

        return $aquarium;
    }

    public static function carpPond(): Aquarium
    {
        $aquarium = new Aquarium(
            id: AquariumId::fromString('4e630e75-6935-41c4-ac85-c1e6c5aa6393'),
            name: new EntityName('Bassin des Carpes'),
            turnNumber: new TurnNumber(12)
        );

        $aquarium->addFish(new Fish(
            id: FishId::fromString('c000d127-1a8f-4b6a-8da6-35faf5de2933'),
            name: new EntityName('Koi Rouge'),
            species: Species::CARP,
            sex: Sex::FEMALE,
            age: new Age(5),
            healthPoints: HealthPoints::initial()
        ));

        $aquarium->addFish(new Fish(
            id: FishId::fromString('d28bd33d-0431-4f31-830b-532f6e100b8b'),
            name: new EntityName('Koi Blanc'),
            species: Species::CARP,
            sex: Sex::MALE,
            age: new Age(4),
            healthPoints: new HealthPoints(9)
        ));

        $aquarium->addAlgae(new Algae(
            id: AlgaeId::fromString('351b8ecd-29be-4c91-8e6f-e1289c157d8b'),
            name: new EntityName('Mousse Aquatique'),
            age: new Age(3),
            healthPoints: new HealthPoints(7)
        ));

        return $aquarium;
    }

    public static function bassPool(): Aquarium
    {
        $aquarium = new Aquarium(
            id: AquariumId::fromString('d978a05b-2762-4935-8b90-d08344def34a'),
            name: new EntityName('Bassin des Bars'),
            turnNumber: new TurnNumber(8)
        );

        $aquarium->addFish(new Fish(
            id: FishId::fromString('cd1a5a9f-427f-4244-b29c-9a6146481100'),
            name: new EntityName('Bulle'),
            species: Species::BASS,
            sex: Sex::FEMALE,
            age: new Age(1),
            healthPoints: new HealthPoints(6)
        ));

        $aquarium->addFish(new Fish(
            id: FishId::fromString('bfbc84b2-6936-4ef2-b295-19b11900c7ac'),
            name: new EntityName('Flash'),
            species: Species::BASS,
            sex: Sex::MALE,
            age: new Age(1),
            healthPoints: new HealthPoints(7)
        ));

        $aquarium->addFish(new Fish(
            id: FishId::fromString('a6601e83-aca0-40b2-a525-a282e79c34d2'),
            name: new EntityName('Nageoire'),
            species: Species::BASS,
            sex: Sex::FEMALE,
            age: new Age(2),
            healthPoints: new HealthPoints(8)
        ));

        $aquarium->addAlgae(new Algae(
            id: AlgaeId::fromString('59b3d85e-cd1a-4879-b34c-bd2819308152'),
            name: new EntityName("Lentille d'eau"),
            age: new Age(1),
            healthPoints: new HealthPoints(4)
        ));

        $aquarium->addAlgae(new Algae(
            id: AlgaeId::fromString('53e3e250-575e-43b7-9a76-b8619051a131'),
            name: new EntityName('Algue Rouge'),
            age: new Age(2),
            healthPoints: new HealthPoints(5)
        ));

        return $aquarium;
    }

    public static function grouperTank(): Aquarium
    {
        $aquarium = new Aquarium(
            id: AquariumId::fromString('f511e488-d0d1-4fb7-8b60-ddb971869355'),
            name: new EntityName('Tank des Mérous'),
            turnNumber: new TurnNumber(20)
        );

        $aquarium->addFish(new Fish(
            id: FishId::fromString('84ce69b9-02da-4dfc-ba4b-72d32d42d74a'),
            name: new EntityName('Goliath'),
            species: Species::GROUPER,
            sex: Sex::MALE,
            age: new Age(10),
            healthPoints: HealthPoints::initial()
        ));

        $aquarium->addFish(new Fish(
            id: FishId::fromString('0e217680-36bc-4ea6-9bba-6e3f55e96c29'),
            name: new EntityName('Titan'),
            species: Species::GROUPER,
            sex: Sex::MALE,
            age: new Age(8),
            healthPoints: new HealthPoints(9)
        ));

        $aquarium->addAlgae(new Algae(
            id: AlgaeId::fromString('2159ea9c-ff4a-44eb-8f66-7fa6e6f7da4b'),
            name: new EntityName('Kelp'),
            age: new Age(5),
            healthPoints: new HealthPoints(8)
        ));

        return $aquarium;
    }

    public static function soleZone(): Aquarium
    {
        $aquarium = new Aquarium(
            id: AquariumId::fromString('8992f586-ec98-472d-8468-521da3aefd07'),
            name: new EntityName('Zone des Soles'),
            turnNumber: new TurnNumber(15)
        );

        $aquarium->addFish(new Fish(
            id: FishId::fromString('810cadb0-daa4-45e5-bd58-d07c53d146d1'),
            name: new EntityName('Silver'),
            species: Species::SOLE,
            sex: Sex::FEMALE,
            age: new Age(3),
            healthPoints: new HealthPoints(7)
        ));

        $aquarium->addFish(new Fish(
            id: FishId::fromString('52b2f162-709b-4ac7-8103-4ddc8291d12d'),
            name: new EntityName('Shadow'),
            species: Species::SOLE,
            sex: Sex::MALE,
            age: new Age(4),
            healthPoints: new HealthPoints(8)
        ));

        $aquarium->addFish(new Fish(
            id: FishId::fromString('f5a8aa1d-896f-4904-ae3a-771c9640dafa'),
            name: new EntityName('Glider'),
            species: Species::SOLE,
            sex: Sex::FEMALE,
            age: new Age(2),
            healthPoints: new HealthPoints(6)
        ));

        $aquarium->addAlgae(new Algae(
            id: AlgaeId::fromString('c4343278-5e7f-4b23-9330-8bd5b8b9246e'),
            name: new EntityName('Spiruline'),
            age: new Age(2),
            healthPoints: new HealthPoints(6)
        ));

        $aquarium->addAlgae(new Algae(
            id: AlgaeId::fromString('b9bbc668-6f51-47b6-8823-013d0ea161b6'),
            name: new EntityName('Chlorella'),
            age: new Age(3),
            healthPoints: new HealthPoints(7)
        ));

        $aquarium->addAlgae(new Algae(
            id: AlgaeId::fromString('b2174142-612a-41b3-b2ce-a55c99bee200'),
            name: new EntityName('Fucus'),
            age: new Age(1),
            healthPoints: new HealthPoints(5)
        ));

        return $aquarium;
    }

    public static function get(string $name): Aquarium
    {
        return match ($name) {
            self::TROPICAL_REEF => self::tropicalReef(),
            self::CARP_POND => self::carpPond(),
            self::BASS_POOL => self::bassPool(),
            self::GROUPER_TANK => self::grouperTank(),
            self::SOLE_ZONE => self::soleZone(),
            default => throw new \InvalidArgumentException('Unknown fixture: ' . $name),
        };
    }

    /**
     * @return Aquarium[]
     */
    public static function all(): array
    {
        return [
            self::tropicalReef(),
            self::carpPond(),
            self::bassPool(),
            self::grouperTank(),
            self::soleZone(),
        ];
    }
}
