<?php

namespace BetterReflectionTest\SourceLocator\Type;

use BetterReflection\Reflector\ClassReflector;
use BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;

/**
 * @covers \BetterReflection\SourceLocator\Type\DirectoriesSourceLocator
 */
class DirectoriesSourceLocatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DirectoriesSourceLocator
     */
    private $sourceLocator;

    public function setUp()
    {
        $this->sourceLocator = new DirectoriesSourceLocator([
            __DIR__ . '/../../Assets/DirectoryScannerAssets',
            __DIR__ . '/../../Assets/DirectoryScannerAssetsFoo',
        ]);
    }

    public function testScanDirectoryClasses()
    {
        $reflector = new ClassReflector($this->sourceLocator);
        $classes = $reflector->getAllClasses();
        $this->assertCount(4, $classes);
        $classNames = [];
        foreach ($classes as $class) {
            $classNames[] = $class->getName();
        }
        sort($classNames);
        $this->assertEquals('BetterReflectionTest\Assets\DirectoryScannerAssetsFoo\Bar\FooBar', $classNames[0]);
        $this->assertEquals('BetterReflectionTest\Assets\DirectoryScannerAssetsFoo\Foo', $classNames[1]);
        $this->assertEquals('BetterReflectionTest\Assets\DirectoryScannerAssets\Bar\FooBar', $classNames[2]);
        $this->assertEquals('BetterReflectionTest\Assets\DirectoryScannerAssets\Foo', $classNames[3]);
    }
}
