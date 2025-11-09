<?php

declare(strict_types=1);

namespace App\Tests\Domain\Fish\ValueObject;

use App\Domain\Fish\ValueObject\Sex;
use PHPUnit\Framework\TestCase;

final class SexTest extends TestCase
{
    public function test_it_has_male_case(): void
    {
        // Then
        $this->assertEquals('MALE', Sex::MALE->value);
    }

    public function test_it_has_female_case(): void
    {
        // Then
        $this->assertEquals('FEMALE', Sex::FEMALE->value);
    }
}
