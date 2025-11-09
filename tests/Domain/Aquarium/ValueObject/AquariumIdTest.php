<?php

declare(strict_types=1);

namespace App\Tests\Domain\Aquarium\ValueObject;

use App\Domain\Aquarium\ValueObject\AquariumId;
use PHPUnit\Framework\TestCase;

final class AquariumIdTest extends TestCase
{
    public function test_it_generates_valid_id(): void
    {
        // When
        $id = AquariumId::generate();

        // Then
        $this->assertInstanceOf(AquariumId::class, $id);
        $this->assertNotEmpty($id->toString());
    }

    public function test_it_creates_from_string(): void
    {
        // Given
        $uuid = AquariumId::generate()->toString();

        // When
        $id = AquariumId::fromString($uuid);

        // Then
        $this->assertSame($uuid, $id->toString());
    }
}
