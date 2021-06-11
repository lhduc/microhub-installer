<?php

namespace Microhub\Composer;

use Composer\Installer\LibraryInstaller as BaseLibraryInstaller;
use Composer\Package\PackageInterface;

/**
 * Class LibraryInstaller
 *
 * @package Microhub\Composer
 */
class LibraryInstaller extends BaseLibraryInstaller
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

        return parent::getInstallPath($package);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($packageType)
    {
        return 'microhub-library' === $packageType;
    }
}
