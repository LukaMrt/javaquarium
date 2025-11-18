<?php

declare(strict_types=1);

namespace App\Lib\Mapper;

use Symfony\Component\ObjectMapper\TransformCallableInterface;

/**
 * @implements TransformCallableInterface<object, object>
 */
class ToStringTransformer implements TransformCallableInterface
{
    public function __invoke(mixed $value, object $source, ?object $target): string
    {
        if (is_object($value) && method_exists($value, '__toString')) {
            return (string)$value;
        }

        throw new \InvalidArgumentException('Value cannot be converted to string');
    }
}
