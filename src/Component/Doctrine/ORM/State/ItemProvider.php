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

namespace Sylius\Component\Resource\Doctrine\ORM\State;

use Psr\Container\ContainerInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\SingleResourceProviderInterface;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\MetadataOption;
use Sylius\Component\Resource\Context\Option\RequestConfigurationOption;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Resource\State\ProviderInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ItemProvider implements ProviderInterface
{
    public function __construct(
        private SingleResourceProviderInterface $singleResourceProvider,
        private ContainerInterface $repositoryLocator,
    ) {
    }

    public function provide(Operation $operation, Context $context): ?object
    {
        $configuration = $context->get(RequestConfigurationOption::class)->configuration();
        $metadata = $context->get(MetadataOption::class)->metadata();

        if (null === $configuration) {
            throw new \RuntimeException('Configuration was not found on the context');
        }

        if (null === $metadata) {
            throw new \RuntimeException('Metadata was not found on the context');
        }

        $repositoryId = sprintf('%s.repository.%s', $metadata->getApplicationName(), $metadata->getName());

        if (!$this->repositoryLocator->has($repositoryId)) {
            throw new \RuntimeException(sprintf('Repository "%s" not found on operation "%s"', $repositoryId, $operation->getName()));
        }

        /** @var RepositoryInterface $repository */
        $repository = $this->repositoryLocator->get($repositoryId);

        if (null === $resource = $this->singleResourceProvider->get($configuration, $repository)) {
            throw new NotFoundHttpException(sprintf('The "%s" has not been found', $metadata->getHumanizedName()));
        }

        return $resource;
    }
}
