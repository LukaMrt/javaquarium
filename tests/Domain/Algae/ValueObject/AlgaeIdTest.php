<?php

declare(strict_types=1);

namespace App\Tests\Domain\Algae\ValueObject;

use App\Domain\Algae\ValueObject\AlgaeId;
use PHPUnit\Framework\TestCase;

final class AlgaeIdTest extends TestCase
{
    public function test_it_generates_valid_id(): void
    {
        // When
        $id = AlgaeId::generate();

        // Then
        $this->assertInstanceOf(AlgaeId::class, $id);
        $this->assertNotEmpty($id->toString());
    }

    public function test_it_creates_from_string(): void
    {
        // Given
        $uuid = AlgaeId::generate()->toString();

        // When
        $id = AlgaeId::fromString($uuid);

        // Then
        $this->assertSame($uuid, $id->toString());
    }
}
