<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Tests\Core\Application\Service\Violation;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ConverterRegistryInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationConverterResolver;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Violation\ViolationConverterInterface;

/**
 * @group Sdk
 * @group Core
 * @group Application
 * @group Service
 * @group Violation
 * @group ViolationConverterResolverTest
 */
class ViolationConverterResolverTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationConverterResolver
     */
    protected ViolationConverterResolver $violationConverterResolver;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Arrange
        $this->violationConverterResolver = new ViolationConverterResolver($this->createConverterRegistryMock());
    }

    /**
     * @return void
     */
    public function testResolve(): void
    {
        // Act
        $violationConverter = $this->violationConverterResolver->resolve($this->createCommandMock());

        // Assert
        $this->assertInstanceOf(ViolationConverterInterface::class, $violationConverter);
    }

    /**
     * @return void
     */
    public function testResolveIfCommandDoesNotHaveConvertor(): void
    {
        // Arrange
        $violationConverterResolver = new ViolationConverterResolver($this->createConverterRegistryMock());

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
        $violationConverterResolver = new ViolationConverterResolver($this->createConverterRegistryMock(false));
        $violationConverter = $violationConverterResolver->resolve($this->createCommandMock());

        $this->assertNull($violationConverter);
    }

    /**
     * @param bool $hasConvertor
     *
     * @return \SprykerSdk\Sdk\Core\Appplication\Dependency\ConverterRegistryInterface
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
                ->method('getViolationConverter')
                ->willReturn($this->createMock(ConverterInterface::class));
        }

        return $command;
    }
}
