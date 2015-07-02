<?php

namespace BetterReflection\Reflector;

use BetterReflection\Reflection\Symbol;
use BetterReflection\Reflector\Generic as GenericReflector;
use BetterReflection\SourceLocator\SourceLocator;

class ClassReflector implements Reflector
{
    /**
     * @var GenericReflector
     */
    private $reflector;

    public function __construct(SourceLocator $sourceLocator)
    {
        $this->reflector = new GenericReflector($sourceLocator);
    }

    /**
     * Create a ReflectionClass for the specified $className
     *
     * @param string $className
     * @return \BetterReflection\Reflection\ReflectionClass
     */
    public function reflect($className)
    {
        $symbol = new Symbol($className, Symbol::SYMBOL_CLASS);
        return $this->reflector->reflect($symbol);
    }

    /**
     * Get all the classes available in the scope specified by the SourceLocator
     *
     * @return \BetterReflection\Reflection\ReflectionClass[]
     */
    public function getAllSymbols()
    {
        return $this->reflector->getAllSymbols(Symbol::SYMBOL_CLASS);
    }
}
