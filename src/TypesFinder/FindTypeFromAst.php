<?php

namespace BetterReflection\TypesFinder;

use phpDocumentor\Reflection\Types\Context;
use PhpParser\Node\Name\FullyQualified;

class FindTypeFromAst
{
    /**
     * Given an AST type, attempt to find a resolved type
     *
     * @todo resolve with context
     * @param $astType
     * @return \phpDocumentor\Reflection\Type|null
     */
    public function __invoke($astType)
    {
        if (is_string($astType)) {
            $typeString = $astType;
        }

        if ($astType instanceof FullyQualified) {
            $typeString = $astType->toString();
        }

        if (!isset($typeString)) {
            return null;
        }

        // @todo https://github.com/Roave/BetterReflection/issues/30
        $types = (new ResolveTypes())->__invoke([$typeString], new Context(''));

        return reset($types);
    }
}
