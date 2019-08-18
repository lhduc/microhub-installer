<?php

namespace Microhub\Composer;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;

/**
 * Class Installer
 *
 * @package Microhub\Composer
 */
class Installer extends LibraryInstaller
{
    /**
     * {@inheritdoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        list($prefix, $type) = explode('-', $package->getType());

        $name = explode('/', $package->getPrettyName());
        $name = isset($name[1]) ? $name[1] : $name[0];

        // If package type prefix is microhub, then check type to return install path
        if ('microhub' === $prefix) {
            switch ($type) {
                case 'service':
                    return 'services/' . $name;
                default:
                    throw new \InvalidArgumentException('Package type is not supported.');
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($packageType)
    {
        return in_array($packageType, [
            'microhub-service',
        ]);
    }
}