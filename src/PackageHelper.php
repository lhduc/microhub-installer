<?php

namespace Microhub\Composer;

use Composer\Composer;
use Composer\Package\PackageInterface;

/**
 * Class PackageHelper
 * @package Microhub\Composer
 */
class PackageHelper
{
    /**
     * Get package install path.
     *
     * @param PackageInterface $package
     * @param Composer $composer
     * @return string|null
     */
    public static function getPackageInstallPath(PackageInterface $package, Composer $composer)
    {
        $prettyName = $package->getPrettyName();
        if (strpos($prettyName, '/') !== false) {
            list($vendor, $name) = explode('/', $prettyName);
        } else {
            $vendor = '';
            $name = $prettyName;
        }

        $availableVars = compact('name', 'vendor');

        $extra = $package->getExtra();
        if (!empty($extra['installer-name'])) {
            $availableVars['name'] = $extra['installer-name'];
        }

        if ($composer->getPackage()) {
            $extra = $composer->getPackage()->getExtra();
            $type = $package->getType();
            if (!empty($extra['installer-paths'])) {
                $customPath = self::mapCustomInstallPaths($extra['installer-paths'], $prettyName, $type);
                if (false !== $customPath) {
                    return self::templatePath($customPath, $availableVars);
                }
            }
        }

        return null;
    }

    /**
     * Search through a passed paths array for a custom install path.
     *
     * @param  array  $paths
     * @param  string $name
     * @param  string $type
     * @param  string $vendor = NULL
     * @return string|false
     */
    public static function mapCustomInstallPaths(array $paths, $name, $type, $vendor = NULL)
    {
        foreach ($paths as $path => $names) {
            $names = (array) $names;
            if (in_array($name, $names) || in_array('type:' . $type, $names) || in_array('vendor:' . $vendor, $names)) {
                return $path;
            }
        }

        return false;
    }

    /**
     * Replace vars in a path
     *
     * @param  string                $path
     * @param  array<string, string> $vars
     * @return string
     */
    public static function templatePath($path, array $vars = array())
    {
        if (strpos($path, '{') !== false) {
            extract($vars);
            preg_match_all('@\{\$([A-Za-z0-9_]*)\}@i', $path, $matches);
            if (!empty($matches[1])) {
                foreach ($matches[1] as $var) {
                    $path = str_replace('{$' . $var . '}', $$var, $path);
                }
            }
        }

        return $path;
    }

    /**
     * @param PackageInterface $package
     * @return array|false|string|string[]
     */
    public static function getNameFromPackage(PackageInterface $package)
    {
        $name = explode('/', $package->getPrettyName());
        $name = isset($name[1]) ? $name[1] : $name[0];

        return str_replace($package->getType() . '-', '', $name);
    }
}
