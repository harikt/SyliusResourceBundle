<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\ResourceBundle\EventListener;

use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Util\RequestConfigurationInitiatorTrait;
use Sylius\Component\Resource\State\ProviderInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ReadListener
{
    use RequestConfigurationInitiatorTrait;

    public function __construct(
        private RegistryInterface $resourceRegistry,
        private RequestConfigurationFactoryInterface $requestConfigurationFactory,
        private ProviderInterface $provider,
        private FactoryInterface $factory,
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (null === $configuration = $this->initializeConfiguration($request)) {
            return;
        }

        if ('create' === $configuration->getOperation()) {
            $data = $this->factory->createNew($configuration);

            $request->attributes->set('data', $data);

            return;
        }

        $data = $this->provider->provide($configuration);

        if (null === $data) {
            throw new NotFoundHttpException('Not Found');
        }

        $request->attributes->set('data', $data);
    }
}
