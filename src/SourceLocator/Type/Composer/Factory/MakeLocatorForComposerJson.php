<?php

declare(strict_types=1);

namespace Roave\BetterReflection\SourceLocator\Type\Composer\Factory;

use Roave\BetterReflection\SourceLocator\Ast\Locator;
use Roave\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\Composer\Factory\Exception\FailedToParseJson;
use Roave\BetterReflection\SourceLocator\Type\Composer\Factory\Exception\InvalidProjectDirectory;
use Roave\BetterReflection\SourceLocator\Type\Composer\Factory\Exception\MissingComposerJson;
use Roave\BetterReflection\SourceLocator\Type\Composer\Psr\Psr0Mapping;
use Roave\BetterReflection\SourceLocator\Type\Composer\Psr\Psr4Mapping;
use Roave\BetterReflection\SourceLocator\Type\Composer\PsrAutoloaderLocator;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\SingleFileSourceLocator;
use function file_exists;
use function is_dir;
use function realpath;

final class MakeLocatorForComposerJson
{
    public function __invoke(string $installationPath, Locator $astLocator)
    {
        $realInstallationPath = (string) realpath($installationPath);

        if (! is_dir($realInstallationPath)) {
            throw InvalidProjectDirectory::atPath($installationPath);
        }

        $composerJsonPath = $realInstallationPath . '/composer.json';

        if (! file_exists($composerJsonPath)) {
            throw MissingComposerJson::inProjectPath($installationPath);
        }

        $composer = json_decode((string) file_get_contents($composerJsonPath), true);

        if (! \is_array($composer)) {
            throw FailedToParseJson::inFile($composerJsonPath);
        }

        $classMapPaths       = $this->prefixWithInstallationPath($this->packageToClassMapPaths($composer), $realInstallationPath);
        $classMapFiles       = array_filter($classMapPaths, 'is_file');
        $classMapDirectories = array_filter($classMapPaths, 'is_dir');
        $filePaths           = $this->prefixWithInstallationPath($this->packageToFilePaths($composer), $realInstallationPath);

        return new AggregateSourceLocator(array_merge(
            [
                new PsrAutoloaderLocator(
                    Psr4Mapping::fromArrayMappings(
                        $this->prefixWithInstallationPath($this->packageToPsr4AutoloadNamespaces($composer), $realInstallationPath)
                    ),
                    $astLocator
                ),
                new PsrAutoloaderLocator(
                    Psr0Mapping::fromArrayMappings(
                        $this->prefixWithInstallationPath($this->packageToPsr0AutoloadNamespaces($composer), $realInstallationPath)
                    ),
                    $astLocator
                ),
                new DirectoriesSourceLocator($classMapDirectories, $astLocator),
            ],
            ...array_map(function (string $file) use ($astLocator) : array {
                return [new SingleFileSourceLocator($file, $astLocator)];
            }, array_merge($classMapFiles, $filePaths))
        ));
    }

    /** @return array<string, array<int, string>> */
    private function packageToPsr4AutoloadNamespaces(array $package) : array
    {
        return array_map(function ($namespacePaths) : array {
            return (array) $namespacePaths;
        }, $package['autoload']['psr-4'] ?? []);
    }

    /** @return array<string, array<int, string>> */
    private function packageToPsr0AutoloadNamespaces(array $package) : array
    {
        return array_map(function ($namespacePaths) : array {
            return (array) $namespacePaths;
        }, $package['autoload']['psr-0'] ?? []);
    }

    /** @return array<string, array<int, string>> */
    private function packageToClassMapPaths(array $package) : array
    {
        return $package['autoload']['classmap'] ?? [];
    }

    /** @return array<string, array<int, string>> */
    private function packageToFilePaths(array $package) : array
    {
        return $package['autoload']['files'] ?? [];
    }

    /**
     * @param array<int|string, string|array<string>> $paths
     *
     * @return array<int|string, string|array<string>>
     */
    private function prefixWithInstallationPath(array $paths, string $trimmedInstallationPath) : array
    {
        return $this->prefixPaths($paths, $trimmedInstallationPath . '/');
    }

    /**
     * @param array<int|string, string|array<string>> $paths
     *
     * @return array<int|string, string|array<string>>
     */
    private function prefixPaths(array $paths, string $prefix) : array
    {
        return array_map(function ($paths) use ($prefix) {
            if (is_array($paths)) {
                return $this->prefixPaths($paths, $prefix);
            }

            return $prefix . $paths;
        }, $paths);
    }
}
