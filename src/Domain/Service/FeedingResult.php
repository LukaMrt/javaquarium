<?php

declare(strict_types=1);

namespace App\Domain\Service;

final readonly class FeedingResult
{
    private function __construct(
        private bool $success,
        private string $reason
    ) {
    }

    public static function success(): self
    {
        return new self(true, 'Feeding successful');
    }

    public static function failure(string $reason): self
    {
        return new self(false, $reason);
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getReason(): string
    {
        return $this->reason;
    }
}
