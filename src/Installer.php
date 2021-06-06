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
		const APP_DIR = 'apps';
    const SERVICE_DIR = 'services';   
    const SHARE_DIR = 'libs';

    /**
     * {@inheritdoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        list($prefix, $type) = explode('-', $package->getType());

        $name = explode('/', $package->getPrettyName());
        $name = isset($name[1]) ? $name[1] : $name[0];

        // Some services have project name in second part. Most of cases is using same git for all projects.
        // Then remove project name in this case.
        $extra = $package->getExtra();
        if (isset($extra['project'])) {
            $name = str_replace($extra['project'] . '-', '', $name);
            $name = str_replace($type . '-', '', $name);
        }

        // If package type prefix is microhub, then check type to return install path
        if ('microhub' === $prefix) {
            switch ($type) {
                case 'application':
                    return static::APP_DIR . '/' . $name; 
                case 'service':
                    return static::SERVICE_DIR . '/' . $name;
                case 'library':
                    return static::SHARE_DIR . '/' . $name;  
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
            'microhub-application',
            'microhub-service',
            'microhub-library',
        ]);
    }
}
