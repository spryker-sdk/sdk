<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Task\TypeStrategy;

use Sdk\Task\Configuration\Loader\ConfigurationLoaderInterface;
use Sdk\Task\ValueResolver\ValueResolverInterface;

class LocalCliTypeStrategy extends AbstractTypeStrategy
{
    /**
     * @var \Sdk\Task\ValueResolver\ValueResolverInterface
     */
    protected ValueResolverInterface $valueResolver;

    /**
     * @param \Sdk\Task\ValueResolver\ValueResolverInterface $valueResolver
     */
    public function __construct(ValueResolverInterface $valueResolver)
    {
        $this->valueResolver = $valueResolver;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'local_cli';
    }

    /**
     * @return array
     */
    public function extract(): array
    {
        $this->definition = $this->valueResolver->expand($this->definition);

        return $this->definition;
    }

    /**
     * @return void
     */
    public function execute(): void
    {
        // task strategy execution
    }
}
