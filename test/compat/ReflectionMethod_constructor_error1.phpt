--TEST--
ReflectionMethod constructor errors
--CREDITS--
Robin Fernandes <robinf@php.net>
Steve Seear <stevseea@php.net>
--FILE--
<?php require 'vendor/autoload.php';

class TestClass
{
    public function foo() {
    }
}


try {
	echo "\nWrong type of argument (bool):\n";
	$methodInfo = \BetterReflection\Reflection\ReflectionMethod::createFromName(true);
} catch (Exception $e) {
	print $e->__toString();
}
try {
	echo "\nWrong type of argument (int):\n";
	$methodInfo = \BetterReflection\Reflection\ReflectionMethod::createFromName(3);
} catch (Exception $e) {
	print $e->__toString();
}
try {
	echo "\nWrong type of argument (bool, string):\n";
	$methodInfo = \BetterReflection\Reflection\ReflectionMethod::createFromName(true, "foo");
} catch (Exception $e) {
	print $e->__toString();
}
try {
	echo "\nWrong type of argument (string, bool):\n";
	$methodInfo = \BetterReflection\Reflection\ReflectionMethod::createFromName('TestClass', true);
} catch (Exception $e) {
	print $e->__toString();
}
try {
	echo "\nNo method given:\n";
	$methodInfo = \BetterReflection\Reflection\ReflectionMethod::createFromName("TestClass");
} catch (Exception $e) {
	print $e->__toString();
}
try {
	echo "\nClass and Method in same string, bad method name:\n";
	$methodInfo = \BetterReflection\Reflection\ReflectionMethod::createFromName("TestClass::foop::dedoop");
} catch (Exception $e) {
	print $e->__toString();
}
try {
	echo "\nClass and Method in same string, bad class name:\n";
	$methodInfo = \BetterReflection\Reflection\ReflectionMethod::createFromName("TestCla::foo");
} catch (Exception $e) {
	print $e->__toString();
}
try {
	echo "\nClass and Method in same string (ok):\n";
	$methodInfo = \BetterReflection\Reflection\ReflectionMethod::createFromName("TestClass::foo");
} catch (Exception $e) {
	print $e->__toString();
}

?>
--EXPECTF--
Wrong type of argument (bool):
ReflectionException: Invalid method name 1 in %s
Stack trace:
#0 %s ReflectionMethod->__construct('1')
#1 {main}
Wrong type of argument (int):
ReflectionException: Invalid method name 3 in %s
Stack trace:
#0 %s ReflectionMethod->__construct('3')
#1 {main}
Wrong type of argument (bool, string):
ReflectionException: The parameter class is expected to be either a string or an object in %s
Stack trace:
#0 %s ReflectionMethod->__construct(true, 'foo')
#1 {main}
Wrong type of argument (string, bool):
ReflectionException: Method TestClass::1() does not exist in %s
Stack trace:
#0 %s ReflectionMethod->__construct('TestClass', '1')
#1 {main}
No method given:
ReflectionException: Invalid method name TestClass in %s
Stack trace:
#0 %s ReflectionMethod->__construct('TestClass')
#1 {main}
Class and Method in same string, bad method name:
ReflectionException: Method TestClass::foop::dedoop() does not exist in %s
Stack trace:
#0 %s ReflectionMethod->__construct('TestClass::foop...')
#1 {main}
Class and Method in same string, bad class name:
ReflectionException: Class TestCla does not exist in %s
Stack trace:
#0 %s ReflectionMethod->__construct('TestCla::foo')
#1 {main}
Class and Method in same string (ok):

