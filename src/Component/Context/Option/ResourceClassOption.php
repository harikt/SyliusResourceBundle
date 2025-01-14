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

namespace Sylius\Component\Resource\Context\Option;

final class ResourceClassOption
{
    /** @param class-string $resourceClass */
    public function __construct(private string $resourceClass)
    {
    }

    /** @return class-string */
    public function resourceClass(): string
    {
        return $this->resourceClass;
    }
}
