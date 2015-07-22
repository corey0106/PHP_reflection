<?php

namespace BetterReflectionTest\SourceLocator;

use BetterReflection\SourceLocator\InternalLocatedSource;

/**
 * @covers \BetterReflection\SourceLocator\EvaledLocatedSource
 */
class EvaledLocatedSourceTest extends \PHPUnit_Framework_TestCase
{
    public function testInternalsLocatedSource()
    {
        $locatedSource = new InternalLocatedSource('foo');

        $this->assertSame('foo', $locatedSource->getSource());
        $this->assertNull($locatedSource->getFileName());
        $this->assertFalse($locatedSource->isInternal());
        $this->assertTrue($locatedSource->isEvaled());
    }
}
