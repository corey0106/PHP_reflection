<?php

declare(strict_types=1);

namespace Roave\BetterReflection\Reflection;

use PhpParser\Node\Identifier;
use PhpParser\Node\IntersectionType;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\UnionType;
use PHPStan\BetterReflection\Reflection\Adapter\Exception\NotImplemented;

abstract class ReflectionType
{
    protected function __construct(private bool $allowsNull)
    {
    }

    public static function createFromTypeAndReflector(Identifier|Name|NullableType|UnionType|IntersectionType $type, bool $forceAllowsNull = false): ReflectionNamedType|ReflectionUnionType
    {
        $allowsNull = $forceAllowsNull;
        if ($type instanceof NullableType) {
            $type       = $type->type;
            $allowsNull = true;
        }

        if ($type instanceof Identifier || $type instanceof Name) {
            return new ReflectionNamedType($type, $allowsNull);
        }

        if ($type instanceof IntersectionType) {
            throw new NotImplemented('Not implemented');
        }

        return new ReflectionUnionType($type, $allowsNull);
    }

    /**
     * Does the parameter allow null?
     */
    public function allowsNull(): bool
    {
        return $this->allowsNull;
    }

    /**
     * Convert this string type to a string
     */
    abstract public function __toString(): string;
}
