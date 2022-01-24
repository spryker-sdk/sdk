<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Tests\Presentation\Console\DependencyInjection;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Presentation\Console\Commands\TaskRunFactoryLoader;
use SprykerSdk\Sdk\Presentation\Console\DependencyInjection\DynamicConsoleCommandsCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class DynamicConsoleCommandsCompilerPassTest extends Unit
{
    /**
     * @return void
     */
    public function testProcessShouldRegisterTaskRunFactoryLoader(): void
    {
        // Arrange
        $compilerPass = new DynamicConsoleCommandsCompilerPass();
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->setDefinition('console.command_loader', new Definition());

        // Act
        $compilerPass->process($containerBuilder);

        // Assert
        $definition = $containerBuilder->getDefinition('console.command_loader');

        $this->assertEquals(TaskRunFactoryLoader::class, $definition->getClass());
    }
}
