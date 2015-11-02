--TEST--
ReflectionParameter::export() with incorrect first parameter
--CREDITS--
Stefan Koopmanschap <stefan@stefankoopmanschap.nl>
--FILE--
<?php require 'vendor/autoload.php';
function ReflectionParameterTest($test, $test2 = null) {
	echo $test;
}
$reflect = \BetterReflection\Reflection\ReflectionFunction::createFromName('ReflectionParameterTest');
$params = $reflect->getParameters();
try {
	foreach($params as $key => $value) {
		ReflectionParameter::export($reflect, $key);
	}
}
catch (ReflectionException $e) {
	echo $e->getMessage() . "\n";
}
try {
	foreach($params as $key => $value) {
		ReflectionParameter::export(42, $key);
	}
}
catch (ReflectionException $e) {
	echo $e->getMessage() . "\n";
}
?>
--EXPECTF--
Method ReflectionFunction::__invoke() does not exist
The parameter class is expected to be either a string, an array(class, method) or a callable object
