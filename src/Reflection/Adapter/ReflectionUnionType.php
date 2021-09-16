<?php

declare(strict_types=1);

namespace Roave\BetterReflection\Reflection\Adapter;

use ReflectionUnionType as CoreReflectionUnionType;
use Roave\BetterReflection\Reflection\ReflectionType as BetterReflectionType;
use Roave\BetterReflection\Reflection\ReflectionUnionType as BetterReflectionUnionType;

use function array_map;

class ReflectionUnionType extends CoreReflectionUnionType
{
    private BetterReflectionUnionType $betterReflectionType;

    public function __construct(BetterReflectionUnionType $betterReflectionType)
    {
        $this->betterReflectionType = $betterReflectionType;
    }

    /**
     * @return array<ReflectionNamedType|ReflectionType|ReflectionUnionType|null>
     */
    public function getTypes(): array
    {
        return array_map(static function (BetterReflectionType $type) {
            return ReflectionType::fromTypeOrNull($type);
        }, $this->betterReflectionType->getTypes());
    }

    public function __toString(): string
    {
        return $this->betterReflectionType->__toString();
    }

    public function allowsNull(): bool
    {
        return $this->betterReflectionType->allowsNull();
    }
}
