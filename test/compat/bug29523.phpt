--TEST--
Reflection Bug #29523 (ReflectionParameter::isOptional() is incorrect)
--FILE--
<?php require 'vendor/autoload.php';

class TestClass
{
}

function optionalTest(TestClass $a, TestClass $b, $c = 3)
{
}

$function = \BetterReflection\Reflection\ReflectionFunction::createFromName('optionalTest');
$numberOfNotOptionalParameters = 0;
$numberOfOptionalParameters = 0;
foreach($function->getParameters() as $parameter)
{
	// @todo see https://github.com/Roave/BetterReflection/issues/155 --- var_dump($parameter->isOptional());
	if ($parameter->isOptional())
	{
		++$numberOfOptionalParameters;
	}
	else
	{
		++$numberOfNotOptionalParameters;
	}
}
// @todo see https://github.com/Roave/BetterReflection/issues/155 --- var_dump($function->getNumberOfRequiredParameters());
// @todo see https://github.com/Roave/BetterReflection/issues/155 --- var_dump($numberOfNotOptionalParameters);

?>
--EXPECT--
bool(false)
bool(false)
bool(true)
int(2)
int(2)
