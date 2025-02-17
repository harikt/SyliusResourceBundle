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

namespace Sylius\Bundle\ResourceBundle\Event;

\class_exists(\Sylius\Component\Resource\Symfony\EventDispatcher\GenericEvent::class);

if (false) {
    class ResourceControllerEvent extends \Sylius\Component\Resource\Symfony\EventDispatcher\GenericEvent
    {
    }
}
