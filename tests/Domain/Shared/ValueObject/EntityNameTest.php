<?php

declare(strict_types=1);

namespace App\Tests\Domain\Shared\ValueObject;

use App\Domain\Shared\ValueObject\EntityName;
use PHPUnit\Framework\TestCase;

final class EntityNameTest extends TestCase
{
    public function test_it_creates_name(): void
    {
        // When
        $name = new EntityName('Nemo');

        // Then
        $this->assertSame('Nemo', $name->toString());
    }

    public function test_equals(): void
    {
        // Given
        $name1 = new EntityName('Nemo');
        $name2 = new EntityName('Nemo');
        $name3 = new EntityName('Nemo2');

        // Then
        $this->assertTrue($name1->equals($name2));
        $this->assertFalse($name1->equals($name3));
    }
}
