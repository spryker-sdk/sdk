<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Presentation\Console\DependencyInjection;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Presentation\Console\Command\TaskLoader\TaskRunFactoryLoader;
use SprykerSdk\Sdk\Presentation\Console\DependencyInjection\DynamicConsoleCommandsCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Presentation
 * @group Console
 * @group DependencyInjection
 * @group DynamicConsoleCommandsCompilerPassTest
 * Add your own group annotations below this line
 */
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
