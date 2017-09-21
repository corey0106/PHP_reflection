<?php

declare(strict_types=1);

namespace Roave\BetterReflection\SourceLocator\Type;

use InvalidArgumentException;
use ReflectionClass;
use Roave\BetterReflection\Identifier\Identifier;
use Roave\BetterReflection\SourceLocator\Ast\Locator;
use Roave\BetterReflection\SourceLocator\Exception\InvalidFileLocation;
use Roave\BetterReflection\SourceLocator\Located\InternalLocatedSource;
use Roave\BetterReflection\SourceLocator\Located\LocatedSource;
use Roave\BetterReflection\SourceLocator\SourceStubber\ReflectionSourceStubber;
use Roave\BetterReflection\SourceLocator\SourceStubber\SourceStubber;
use function class_exists;
use function interface_exists;
use function trait_exists;

final class PhpInternalSourceLocator extends AbstractSourceLocator
{
    /** @var SourceStubber */
    private $stubber;

    public function __construct(Locator $astLocator, ?SourceStubber $stubber = null)
    {
        parent::__construct($astLocator);

        $this->stubber = $stubber ?? new ReflectionSourceStubber();
    }

    /**
     * {@inheritDoc}
     *
     * @throws InvalidArgumentException
     * @throws InvalidFileLocation
     */
    protected function createLocatedSource(Identifier $identifier) : ?LocatedSource
    {
        $classReflection = $this->getInternalReflectionClass($identifier);

        if ($classReflection === null) {
            return null;
        }

        $stub = $this->stubber->generateClassStub($classReflection);

        if ($stub === null) {
            return null;
        }

        return new InternalLocatedSource(
            "<?php\n\n" . $stub,
            $classReflection->getExtensionName()
        );
    }

    private function getInternalReflectionClass(Identifier $identifier) : ?ReflectionClass
    {
        if (! $identifier->isClass()) {
            return null;
        }

        $name = $identifier->getName();

        if (! (class_exists($name, false) || interface_exists($name, false) || trait_exists($name, false))) {
            return null; // not an available internal class
        }

        $reflection = new ReflectionClass($name);

        return $reflection->isInternal() ? $reflection : null;
    }
}
