<?php # inspired by https://github.com/Roave/BetterReflection/issues/276

# parse all classes in a directory that use some dependencies from /vendor

require_once __DIR__ . '/vendor/autoload.php';

use Roave\BetterReflection\Configuration;
use Roave\BetterReflection\Reflector\ClassReflector;
use Roave\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\AutoloadSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;

$directories = [__DIR__ . '/src'];

$sourceLocator = new AggregateSourceLocator([
    new DirectoriesSourceLocator($directories),
    // ↓ required to autoload parent classes/interface from another directory than /src (e.g. /vendor)
    new AutoloadSourceLocator((new Configuration())->astLocator())
]);

$classReflector = new ClassReflector($sourceLocator);

$classReflections = $classReflector->getAllClasses();
