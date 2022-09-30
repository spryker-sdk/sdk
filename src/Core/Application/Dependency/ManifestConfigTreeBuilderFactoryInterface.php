<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dependency;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;

interface ManifestConfigTreeBuilderFactoryInterface
{
    /**
     * Returns manifest name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Generates the configuration tree builder.
     *
     * @param array $config
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    public function getConfigTreeBuilder(array $config): TreeBuilder;
}
