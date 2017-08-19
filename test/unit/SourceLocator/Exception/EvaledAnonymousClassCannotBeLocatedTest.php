<?php
declare(strict_types=1);

namespace Roave\BetterReflectionTest\SourceLocator\Exception;

use Roave\BetterReflection\SourceLocator\Exception\EvaledAnonymousClassCannotBeLocated;
use PHPUnit_Framework_TestCase;

/**
 * @covers \Roave\BetterReflection\SourceLocator\Exception\EvaledAnonymousClassCannotBeLocated
 */
class EvaledAnonymousClassCannotBeLocatedTest extends PHPUnit_Framework_TestCase
{
    public function testCreate() : void
    {
        $exception = EvaledAnonymousClassCannotBeLocated::create();

        self::assertInstanceOf(EvaledAnonymousClassCannotBeLocated::class, $exception);
        self::assertSame('Evaled anonymous class cannot be located', $exception->getMessage());
    }
}
