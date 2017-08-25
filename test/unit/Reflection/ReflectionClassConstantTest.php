<?php
declare(strict_types=1);

namespace Roave\BetterReflectionTest\Reflection;

use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use Roave\BetterReflection\Reflection\ReflectionClassConstant;
use Roave\BetterReflection\Reflector\ClassReflector;
use Roave\BetterReflection\SourceLocator\Type\ComposerSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\StringSourceLocator;
use Roave\BetterReflectionTest\Fixture\ExampleClass;

class ReflectionClassConstantTest extends TestCase
{
    private function getComposerLocator() : ComposerSourceLocator
    {
        return new ComposerSourceLocator(
            require __DIR__ . '/../../../vendor/autoload.php'
        );
    }

    private function getExampleConstant(string $name) : ?ReflectionClassConstant
    {
        $reflector = new ClassReflector($this->getComposerLocator());
        $classInfo = $reflector->reflect(ExampleClass::class);
        return $classInfo->getReflectionConstant($name);
    }

    public function testDefaultVisibility() : void
    {
        $const = $this->getExampleConstant('MY_CONST_1');
        $this->assertTrue($const->isPublic());
    }

    public function testPublicVisibility() : void
    {
        $const = $this->getExampleConstant('MY_CONST_3');
        $this->assertTrue($const->isPublic());
    }

    public function testProtectedVisibility() : void
    {
        $const = $this->getExampleConstant('MY_CONST_4');
        $this->assertTrue($const->isProtected());
    }

    public function testPrivateVisibility() : void
    {
        $const = $this->getExampleConstant('MY_CONST_5');
        $this->assertTrue($const->isPrivate());
    }

    /**
     * @param string $const
     * @param string $expected
     * @dataProvider toStringProvider
     */
    public function testToString(string $const, string $expected) : void
    {
        $const = $this->getExampleConstant($const);
        $this->assertSame($expected, (string) $const);
    }

    public function toStringProvider() : array
    {
        return [
            ['MY_CONST_1', 'Constant [ public integer MY_CONST_1 ] { 123 }' . PHP_EOL],
            ['MY_CONST_3', 'Constant [ public integer MY_CONST_3 ] { 345 }' . PHP_EOL],
            ['MY_CONST_4', 'Constant [ protected integer MY_CONST_4 ] { 456 }' . PHP_EOL],
            ['MY_CONST_5', 'Constant [ private integer MY_CONST_5 ] { 567 }' . PHP_EOL],
        ];
    }

    /**
     * @param string $const
     * @param int $expected
     * @dataProvider getModifiersProvider
     */
    public function testGetModifiers(string $const, int $expected) : void
    {
        $const = $this->getExampleConstant($const);
        $this->assertSame($expected, $const->getModifiers());
    }

    public function getModifiersProvider() : array
    {
        return [
            ['MY_CONST_1', ReflectionProperty::IS_PUBLIC],
            ['MY_CONST_3', ReflectionProperty::IS_PUBLIC],
            ['MY_CONST_4', ReflectionProperty::IS_PROTECTED],
            ['MY_CONST_5', ReflectionProperty::IS_PRIVATE],
        ];
    }

    public function testGetDocComment() : void
    {
        $const = $this->getExampleConstant('MY_CONST_2');
        $this->assertContains('Documentation for constant', $const->getDocComment());
    }

    public function testGetDocCommentReturnsEmptyStringWithNoComment() : void
    {
        $const = $this->getExampleConstant('MY_CONST_1');
        $this->assertSame('', $const->getDocComment());
    }

    public function testGetDeclaringClass() : void
    {
        $reflector = new ClassReflector($this->getComposerLocator());
        $classInfo = $reflector->reflect(ExampleClass::class);
        $const     = $classInfo->getReflectionConstant('MY_CONST_1');
        $this->assertSame($classInfo, $const->getDeclaringClass());
    }

    /**
     * @param string $php
     * @param int $startLine
     * @param int $endLine
     * @dataProvider startEndLineProvider
     */
    public function testStartEndLine(string $php, int $startLine, int $endLine) : void
    {
        $reflector       = new ClassReflector(new StringSourceLocator($php));
        $classReflection = $reflector->reflect('\T');
        $constReflection = $classReflection->getReflectionConstant('TEST');
        $this->assertEquals($startLine, $constReflection->getStartLine());
        $this->assertEquals($endLine, $constReflection->getEndLine());
    }

    public function startEndLineProvider() : array
    {
        return [
            ["<?php\nclass T {\nconst TEST = 1; }", 3, 3],
            ["<?php\n\nclass T {\nconst TEST = 1; }", 4, 4],
            ["<?php\nclass T {\nconst TEST = \n1; }", 3, 4],
            ["<?php\nclass T {\nconst \nTEST = 1; }", 3, 4],
        ];
    }

    public function columsProvider() : array
    {
        return [
            ["<?php\n\nclass T {\nconst TEST = 1;}", 1, 15],
            ["<?php\n\n    class T {\n        const TEST = 1;}", 9, 23],
            ['<?php class T {const TEST = 1;}', 16, 30],
        ];
    }

    /**
     * @param string $php
     * @param int $startColumn
     * @param int $endColumn
     * @dataProvider columsProvider
     */
    public function testGetStartColumnAndEndColumn(string $php, int $startColumn, int $endColumn) : void
    {
        $reflector          = new ClassReflector(new StringSourceLocator($php));
        $classReflection    = $reflector->reflect('T');
        $constantReflection = $classReflection->getReflectionConstant('TEST');

        self::assertEquals($startColumn, $constantReflection->getStartColumn());
        self::assertEquals($endColumn, $constantReflection->getEndColumn());
    }
}
