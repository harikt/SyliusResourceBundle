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

namespace spec\Sylius\Component\Resource\Metadata;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Metadata\MetadataInterface;

final class MetadataSpec extends ObjectBehavior
{
    function let(): void
    {
        $this->beConstructedThrough('fromAliasAndConfiguration', [
            'app.product',
            [
                'driver' => 'doctrine/orm',
                'state_machine_component' => 'symfony',
                'templates' => 'AppBundle:Resource',
                'classes' => [
                    'model' => 'AppBundle\Model\Resource',
                    'form' => [
                        'default' => 'AppBundle\Form\Type\ResourceType',
                        'choice' => 'AppBundle\Form\Type\ResourceChoiceType',
                        'autocomplete' => 'AppBundle\Type\ResourceAutocompleteType',
                    ],
                ],
            ],
        ]);
    }

    function it_implements_metadata_interface(): void
    {
        $this->shouldImplement(MetadataInterface::class);
    }

    function it_has_alias(): void
    {
        $this->getAlias()->shouldReturn('app.product');
    }

    function it_allows_to_have_alias_with_dot_in_name(): void
    {
        $this->beConstructedThrough('fromAliasAndConfiguration', [
            'app.product.with.dots',
            [
                'driver' => 'doctrine/orm',
                'templates' => 'AppBundle:Resource',
                'classes' => [
                    'model' => 'AppBundle\Model\Resource',
                    'form' => [
                        'default' => 'AppBundle\Form\Type\ResourceType',
                        'choice' => 'AppBundle\Form\Type\ResourceChoiceType',
                        'autocomplete' => 'AppBundle\Type\ResourceAutocompleteType',
                    ],
                ],
            ],
        ]);

        $this->getAlias()->shouldReturn('app.product.with.dots');
        $this->getApplicationName()->shouldReturn('app');
        $this->getName()->shouldReturn('product.with.dots');
    }

    function it_has_application_name(): void
    {
        $this->getApplicationName()->shouldReturn('app');
    }

    function it_has_resource_name(): void
    {
        $this->getName()->shouldReturn('product');
    }

    function it_humanizes_simple_names(): void
    {
        $this->getHumanizedName()->shouldReturn('product');
    }

    function it_humanizes_more_complex_names(): void
    {
        $this->beConstructedThrough('fromAliasAndConfiguration', ['app.product_option', ['driver' => 'doctrine/orm']]);

        $this->getHumanizedName()->shouldReturn('product option');
    }

    function it_has_plural_resource_name(): void
    {
        $this->getPluralName()->shouldReturn('products');
    }

    function it_has_driver(): void
    {
        $this->getDriver()->shouldReturn('doctrine/orm');
    }

    function it_has_state_machine_component(): void
    {
        $this->getStateMachineComponent()->shouldReturn('symfony');
    }

    function it_has_templates_namespace(): void
    {
        $this->getTemplatesNamespace()->shouldReturn('AppBundle:Resource');
    }

    function it_has_access_to_specific_config_parameter(): void
    {
        $this->getParameter('driver')->shouldReturn('doctrine/orm');
    }

    function it_checks_if_specific_parameter_exists(): void
    {
        $this->hasParameter('foo')->shouldReturn(false);
        $this->hasParameter('driver')->shouldReturn(true);
    }

    function it_throws_an_exception_when_parameter_does_not_exist(): void
    {
        $this
            ->shouldThrow(\InvalidArgumentException::class)
            ->during('getParameter', ['foo'])
        ;
    }

    function it_has_access_to_specific_classes(): void
    {
        $this->getClass('model')->shouldReturn('AppBundle\Model\Resource');
    }

    function it_throws_an_exception_when_class_does_not_exist(): void
    {
        $this
            ->shouldThrow(\InvalidArgumentException::class)
            ->during('getClass', ['foo'])
        ;
    }

    function it_checks_if_specific_class_exists(): void
    {
        $this->hasClass('bar')->shouldReturn(false);
        $this->hasClass('model')->shouldReturn(true);
    }

    function it_generates_service_id(): void
    {
        $this->getServiceId('factory')->shouldReturn('app.factory.product');
        $this->getServiceId('repository')->shouldReturn('app.repository.product');
        $this->getServiceId('form.type')->shouldReturn('app.form.type.product');
    }

    function it_generates_permission_code(): void
    {
        $this->getPermissionCode('show')->shouldReturn('app.product.show');
        $this->getPermissionCode('create')->shouldReturn('app.product.create');
        $this->getPermissionCode('custom')->shouldReturn('app.product.custom');
    }
}
