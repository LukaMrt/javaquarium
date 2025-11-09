<?php

declare(strict_types=1);

namespace App\Tests\Domain\Fish\ValueObject;

use App\Domain\Fish\ValueObject\FishId;
use PHPUnit\Framework\TestCase;

final class FishIdTest extends TestCase
{
    public function test_it_generates_valid_id(): void
    {
        // When
        $id = FishId::generate();

        // Then
        $this->assertInstanceOf(FishId::class, $id);
        $this->assertNotEmpty($id->toString());
    }

    public function test_it_creates_from_string(): void
    {
        // Given
        $uuid = FishId::generate()->toString();

        // When
        $id = FishId::fromString($uuid);

        // Then
        $this->assertSame($uuid, $id->toString());
    }
}
