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

namespace Sylius\Bundle\ResourceBundle\Grid\View;

use Sylius\Bundle\ResourceBundle\Controller\ParametersParserInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Component\Grid\Data\DataProviderInterface;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Resource\Metadata\MetadataInterface;

final class ResourceGridViewFactory implements ResourceGridViewFactoryInterface
{
    private DataProviderInterface $dataProvider;

    private ParametersParserInterface $parametersParser;

    public function __construct(DataProviderInterface $dataProvider, ParametersParserInterface $parametersParser)
    {
        $this->dataProvider = $dataProvider;
        $this->parametersParser = $parametersParser;
    }

    public function create(
        Grid $grid,
        Parameters $parameters,
        MetadataInterface $metadata,
        RequestConfiguration $requestConfiguration,
    ): ResourceGridView {
        $driverConfiguration = $grid->getDriverConfiguration();
        $request = $requestConfiguration->getRequest();

        $grid->setDriverConfiguration($this->parametersParser->parseRequestValues($driverConfiguration, $request));

        return new ResourceGridView($this->dataProvider->getData($grid, $parameters), $grid, $parameters, $metadata, $requestConfiguration);
    }
}
