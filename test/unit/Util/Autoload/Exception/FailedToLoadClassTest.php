<?php

namespace Roave\BetterReflectionTest\Util\Autoload\Exception;

use Roave\BetterReflection\Util\Autoload\Exception\FailedToLoadClass;

/**
 * @covers \Roave\BetterReflection\Util\Autoload\Exception\FailedToLoadClass
 */
class FailedToLoadClassTest extends \PHPUnit_Framework_TestCase
{
    public function testFromReflectionClass()
    {
        $className = uniqid('class name', true);

        $exception = FailedToLoadClass::fromClassName($className);

        self::assertInstanceOf(FailedToLoadClass::class, $exception);
        self::assertSame(
            sprintf('Unable to load class %s', $className),
            $exception->getMessage()
        );
    }
}
