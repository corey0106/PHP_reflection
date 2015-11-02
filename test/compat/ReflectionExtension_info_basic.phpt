--TEST--
ReflectionExtension::info()
--CREDITS--
Gerrit "Remi" te Sligte <remi@wolerized.com>
Leon Luijkx <leon@phpgg.nl>
--FILE--
<?php require 'vendor/autoload.php';
$obj = \BetterReflection\Reflection\ReflectionExtension::createFromName('reflection');
ob_start();
$testa = $obj->info();
$testb = ob_get_clean();
var_dump($testa);
var_dump(strlen($testb) > 24);
?>
==DONE==
--EXPECT--
NULL
bool(true)
==DONE==
