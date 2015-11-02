--TEST--
ReflectionClass::isUserDefined() - invalid params.
--FILE--
<?php require 'vendor/autoload.php';
$r1 = \BetterReflection\Reflection\ReflectionClass::createFromName("stdClass");
var_dump($r1->isUserDefined('X'));
var_dump($r1->isUserDefined('X', true));
?>
--EXPECTF--
Warning: ReflectionClass::isUserDefined() expects exactly 0 parameters, 1 given in %s on line %d
NULL

Warning: ReflectionClass::isUserDefined() expects exactly 0 parameters, 2 given in %s on line %d
NULL
