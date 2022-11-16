<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Core\Application\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\ConverterRegistryInterface;
use SprykerSdk\Sdk\Core\Application\Service\ConverterResolver;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Report\ReportConverterInterface;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Core
 * @group Application
 * @group Service
 * @group ConverterResolverTest
 * Add your own group annotations below this line
 */
class ConverterResolverTest extends Unit
{
    /**
     * @return void
     */
    public function testReturnsNullWhenCommandConverterIsNull(): void
    {
        //Arrange
        $command = $this->createCommandMock();
        $converterRegistry = $this->createConverterRegistryMock();
        $converterResolver = new ConverterResolver($converterRegistry);

        //Act
        $reportConverter = $converterResolver->resolve($command);

        //Assert
        $this->assertNull($reportConverter);
    }

    /**
     * @return void
     */
    public function testReturnsNullWhenRegistryHasNoReportConverter(): void
    {
        //Arrange
        $converter = $this->createConverterMock('test');
        $command = $this->createCommandMock($converter);
        $converterRegistry = $this->createConverterRegistryMock();
        $converterRegistry->method('has')->willReturn(false);

        $converterResolver = new ConverterResolver($converterRegistry);

        //Act
        $reportConverter = $converterResolver->resolve($command);

        //Assert
        $this->assertNull($reportConverter);
    }

    /**
     * @return void
     */
    public function testReturnsNullWhenRegistryHasNullConverter(): void
    {
        //Arrange
        $converter = $this->createConverterMock('test');
        $command = $this->createCommandMock($converter);

        $converterRegistry = $this->createConverterRegistryMock();
        $converterRegistry->method('has')->willReturn(true);
        $converterRegistry->method('get')->willReturn(null);

        $converterResolver = new ConverterResolver($converterRegistry);

        //Act
        $reportConverter = $converterResolver->resolve($command);

        //Assert
        $this->assertNull($reportConverter);
    }

    /**
     * @return void
     */
    public function testReturnsReportConverterWhenItExists(): void
    {
        //Arrange
        $converter = $this->createConverterMock('test');
        $command = $this->createCommandMock($converter);
        $reportConverter = $this->createReportConverterMock();

        $converterRegistry = $this->createConverterRegistryMock();
        $converterRegistry->method('has')->willReturn(true);
        $converterRegistry->method('get')->willReturn($reportConverter);

        $converterResolver = new ConverterResolver($converterRegistry);

        //Act
        $returnedReportConverter = $converterResolver->resolve($command);

        //Assert
        $this->assertSame($reportConverter, $returnedReportConverter);
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Application\Dependency\ConverterRegistryInterface|\Sdk\Unit\Core\Application\Service\MockObject
     */
    protected function createConverterRegistryMock(): ConverterRegistryInterface
    {
        return $this->createMock(ConverterRegistryInterface::class);
    }

    /**
     * @param string $name
     *
     * @return \SprykerSdk\SdkContracts\Entity\ConverterInterface
     */
    protected function createConverterMock(string $name): ConverterInterface
    {
        $converterMock = $this->createMock(ConverterInterface::class);
        $converterMock->method('getName')->willReturn($name);

        return $converterMock;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ConverterInterface|null $converter
     *
     * @return \SprykerSdk\SdkContracts\Entity\CommandInterface
     */
    protected function createCommandMock(?ConverterInterface $converter = null): CommandInterface
    {
        $commandMock = $this->createMock(CommandInterface::class);
        $commandMock->method('getConverter')->willReturn($converter);

        return $commandMock;
    }

    /**
     * @return \SprykerSdk\SdkContracts\Report\ReportConverterInterface
     */
    protected function createReportConverterMock(): ReportConverterInterface
    {
        return $this->createMock(ReportConverterInterface::class);
    }
}
