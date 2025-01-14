<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\ResourceBundle\DependencyInjection\Driver;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\Doctrine\DoctrineODMDriver;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\Doctrine\DoctrineORMDriver;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\Doctrine\DoctrinePHPCRDriver;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\Exception\UnknownDriverException;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Webmozart\Assert\Assert;

final class DriverProvider
{
    /** @var DriverInterface[] */
    private static array $drivers = [];

    /**
     * @throws UnknownDriverException
     */
    public static function get(MetadataInterface $metadata): DriverInterface
    {
        $type = $metadata->getDriver();

        if (isset(self::$drivers[$type])) {
            return self::$drivers[$type];
        }

        Assert::notFalse($type, sprintf('No driver was configured on the resource "%s".', $metadata->getAlias()));

        switch ($type) {
            case SyliusResourceBundle::DRIVER_DOCTRINE_ORM:
                return self::$drivers[$type] = new DoctrineORMDriver();
            case SyliusResourceBundle::DRIVER_DOCTRINE_MONGODB_ODM:
                return self::$drivers[$type] = new DoctrineODMDriver();
            case SyliusResourceBundle::DRIVER_DOCTRINE_PHPCR_ODM:
                return self::$drivers[$type] = new DoctrinePHPCRDriver();
        }

        throw new UnknownDriverException($type);
    }
}
