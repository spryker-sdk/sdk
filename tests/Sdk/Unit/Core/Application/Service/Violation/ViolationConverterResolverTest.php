<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Core\Application\Service\Violation;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\ConverterRegistryInterface;
use SprykerSdk\Sdk\Core\Application\Service\ConverterResolver;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Report\Violation\ViolationConverterInterface;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Core
 * @group Application
 * @group Service
 * @group Violation
 * @group ViolationConverterResolverTest
 * Add your own group annotations below this line
 */
class ViolationConverterResolverTest extends Unit
{
    /**
     * @return void
     */
    public function testResolve(): void
    {
        // Arrange
        $violationConverterResolver = new ConverterResolver($this->createConverterRegistryMock());

        // Act
        $violationConverter = $violationConverterResolver->resolve($this->createCommandMock());

        // Assert
        $this->assertInstanceOf(ViolationConverterInterface::class, $violationConverter);
    }

    /**
     * @return void
     */
    public function testResolveIfCommandDoesNotHaveConvertor(): void
    {
        // Arrange
        $violationConverterResolver = new ConverterResolver($this->createConverterRegistryMock());

        // Act
        $violationConverter = $violationConverterResolver->resolve($this->createCommandMock(false));

        // Assert
        $this->assertNull($violationConverter);
    }

    /**
     * @return void
     */
    public function testResolveIfConvertorDoesNotExist(): void
    {
        $violationConverterResolver = new ConverterResolver($this->createConverterRegistryMock(false));
        $violationConverter = $violationConverterResolver->resolve($this->createCommandMock());

        $this->assertNull($violationConverter);
    }

    /**
     * @param bool $hasConvertor
     *
     * @return \SprykerSdk\Sdk\Core\Application\Dependency\ConverterRegistryInterface
     */
    protected function createConverterRegistryMock(bool $hasConvertor = true): ConverterRegistryInterface
    {
        $converterRegistry = $this->createMock(ConverterRegistryInterface::class);
        $converterRegistry
            ->method('has')
            ->willReturn($hasConvertor);
        $converterRegistry
            ->method('get')
            ->willReturn($this->createMock(ViolationConverterInterface::class));

        return $converterRegistry;
    }

    /**
     * @param bool $hasConvertor
     *
     * @return \SprykerSdk\SdkContracts\Entity\CommandInterface
     */
    protected function createCommandMock(bool $hasConvertor = true): CommandInterface
    {
        $command = $this->createMock(CommandInterface::class);

        if ($hasConvertor) {
            $command
                ->method('getConverter')
                ->willReturn($this->createMock(ConverterInterface::class));
        }

        return $command;
    }
}
