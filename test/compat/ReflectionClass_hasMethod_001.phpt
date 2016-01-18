--TEST--
ReflectionClass::hasMethod()
--CREDITS--
Robin Fernandes <robinf@php.net>
Steve Seear <stevseea@php.net>
--FILE--
<?php require 'vendor/autoload.php';
class pubf {
	public function f() {}
	static public function s() {}	
}
class subpubf extends pubf {
}

class protf {
	protected function f() {}
	static protected function s() {}	
}
class subprotf extends protf {
}

class privf {
	private function f() {}
	static private function s() {}
}
class subprivf extends privf  {
}

$classes = array("pubf", "subpubf", "protf", "subprotf", 
				 "privf", "subprivf");
foreach($classes as $class) {
	echo "Reflecting on class $class: \n";
	$rc = \BetterReflection\Reflection\ReflectionClass::createFromName($class);
	echo "  --> Check for f(): ";
	// @todo see https://github.com/Roave/BetterReflection/issues/155 --- var_dump($rc->hasMethod("f"));
	echo "  --> Check for s(): ";
	// @todo see https://github.com/Roave/BetterReflection/issues/155 --- var_dump($rc->hasMethod("s"));
	echo "  --> Check for F(): ";
	// @todo see https://github.com/Roave/BetterReflection/issues/155 --- var_dump($rc->hasMethod("F"));
	echo "  --> Check for doesntExist(): ";
	// @todo see https://github.com/Roave/BetterReflection/issues/155 --- var_dump($rc->hasMethod("doesntExist"));
}
?>
--EXPECTF--
Reflecting on class pubf: 
  --> Check for f(): bool(true)
  --> Check for s(): bool(true)
  --> Check for F(): bool(true)
  --> Check for doesntExist(): bool(false)
Reflecting on class subpubf: 
  --> Check for f(): bool(true)
  --> Check for s(): bool(true)
  --> Check for F(): bool(true)
  --> Check for doesntExist(): bool(false)
Reflecting on class protf: 
  --> Check for f(): bool(true)
  --> Check for s(): bool(true)
  --> Check for F(): bool(true)
  --> Check for doesntExist(): bool(false)
Reflecting on class subprotf: 
  --> Check for f(): bool(true)
  --> Check for s(): bool(true)
  --> Check for F(): bool(true)
  --> Check for doesntExist(): bool(false)
Reflecting on class privf: 
  --> Check for f(): bool(true)
  --> Check for s(): bool(true)
  --> Check for F(): bool(true)
  --> Check for doesntExist(): bool(false)
Reflecting on class subprivf: 
  --> Check for f(): bool(true)
  --> Check for s(): bool(true)
  --> Check for F(): bool(true)
  --> Check for doesntExist(): bool(false)
