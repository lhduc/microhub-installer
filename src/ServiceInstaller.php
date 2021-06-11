<?php

namespace Microhub\Composer;

use Composer\Installer\LibraryInstaller as BaseLibraryInstaller;
use Composer\Package\PackageInterface;

/**
 * Class Installer
 *
 * @package Microhub\Composer
 */
class ServiceInstaller extends BaseLibraryInstaller
{
    /**
     * {@inheritdoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        $path = PackageHelper::getPackageInstallPath($package, $this->composer);
        if (!empty($path)) {
            return $path;
        }

        $name = PackageHelper::getNameFromPackage($package);

        return 'services/' . $name;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($packageType)
    {
        return 'microhub-service' === $packageType;
    }
}
