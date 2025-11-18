<?php

declare(strict_types=1);

namespace App\Lib\Mapper;

use Symfony\Component\ObjectMapper\TransformCallableInterface;

/**
 * @implements TransformCallableInterface<object, object>
 */
class ToIntTransformer implements TransformCallableInterface
{
    public function __invoke(mixed $value, object $source, ?object $target): int
    {
        if (is_object($value) && method_exists($value, 'toInt') && is_int($value->toInt())) {
            return $value->toInt();
        }

        throw new \InvalidArgumentException('Value cannot be converted to int');
    }
}
