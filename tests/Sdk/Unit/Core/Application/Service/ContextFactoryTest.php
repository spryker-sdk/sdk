<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Core\Application\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\ContextRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Service\ContextFactory;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

/**
 * @group Sdk
 * @group Core
 * @group Application
 * @group Service
 * @group ContextStorageTest
 */
class ContextFactoryTest extends Unit
{
    /**
     * @return void
     */
    public function testGetContextFromRepository(): void
    {
        //Arrange
        $context = $this->createMock(ContextInterface::class);
        $contextRepository = $this->createMock(ContextRepositoryInterface::class);
        $contextRepository
            ->expects($this->once())
            ->method('findByName')
            ->willReturn($context);

        $contextFactory = new ContextFactory($contextRepository);

        //Act
        $foundContext = $contextFactory->getContext('test');

        //Assert
        $this->assertEquals($context, $foundContext);
    }

    /**
     * @return void
     */
    public function testGetContext(): void
    {
        //Arrange
        $contextRepository = $this->createMock(ContextRepositoryInterface::class);
        $contextRepository
            ->expects($this->never())
            ->method('findByName');

        $contextFactory = new ContextFactory($contextRepository);

        //Act
        $foundContext = $contextFactory->getContext();

        //Assert
        $this->assertEquals(new Context(), $foundContext);
    }

    /**
     * @return void
     */
    public function testGetContextReturnsCorrectContextInstanceIfContextFilePathProvidedAndContextExists(): void
    {
        //Arrange
        $expectedPathSpecificContext = new Context();
        $expectedPathSpecificContext->setName('path-specific-context');

        $contextRepository = $this->createMock(ContextRepositoryInterface::class);
        $contextRepository
            ->expects($this->once())
            ->method('findByName')
            ->willReturn($expectedPathSpecificContext);

        $contextFactory = new ContextFactory($contextRepository);

        //Act
        $contextFactory->getContext();
        $realPathSpecificContext = $contextFactory->getContext($expectedPathSpecificContext->getName());

        //Assert
        $this->assertEquals($expectedPathSpecificContext, $realPathSpecificContext);
    }

    /**
     * @return void
     */
    public function testGetContextReturnsNewContextInstanceIfContextFilePathProvidedAndContextDoesNotExists(): void
    {
        //Arrange
        $contextRepository = $this->createMock(ContextRepositoryInterface::class);
        $contextRepository
            ->expects($this->once())
            ->method('findByName')
            ->willReturn(null);

        $contextFactory = new ContextFactory($contextRepository);

        //Act
        $actualContext = $contextFactory->getContext('not-existing-context-file-path');

        //Assert
        $this->assertEquals(new Context(), $actualContext);
    }
}
