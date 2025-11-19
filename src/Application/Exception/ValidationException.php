<?php

declare(strict_types=1);

namespace App\Application\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ValidationException extends \RuntimeException
{
    public function __construct(
        private readonly ConstraintViolationListInterface $violations,
        string $message = 'Validation failed',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }

    public function getViolationsAsString(): string
    {
        $messages = [];
        foreach ($this->violations as $violation) {
            $messages[] = sprintf(
                '%s: %s',
                $violation->getPropertyPath(),
                $violation->getMessage()
            );
        }

        return implode(', ', $messages);
    }
}
