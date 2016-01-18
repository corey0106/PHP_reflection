--TEST--
ReflectionClass::getName() - invalid params
--FILE--
<?php require 'vendor/autoload.php';

$r1 = \BetterReflection\Reflection\ReflectionClass::createFromName("stdClass");

// @todo see https://github.com/Roave/BetterReflection/issues/155 --- var_dump($r1->getName('X'));
// @todo see https://github.com/Roave/BetterReflection/issues/155 --- var_dump($r1->getName('X', true));
?> 
--EXPECTF--
Warning: ReflectionClass::getName() expects exactly 0 parameters, 1 given in %s on line %d
NULL

Warning: ReflectionClass::getName() expects exactly 0 parameters, 2 given in %s on line %d
NULL
