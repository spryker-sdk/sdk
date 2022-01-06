<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Tests\Core\Application\Service\Violation;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ConverterRegistryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationConverterResolver;
use SprykerSdk\Sdk\Core\Domain\Entity\Converter;
use SprykerSdk\Sdk\Tests\UnitTester;
use SprykerSdk\SdkContracts\Violation\ViolationConverterInterface;

class ViolationConverterResolverTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationConverterResolver
     */
    protected ViolationConverterResolver $violationConverterResolver;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ConverterRegistryInterface
     */
    protected ConverterRegistryInterface $converterRegistry;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->converterRegistry = $this->createMock(ConverterRegistryInterface::class);
        $this->settingRepository = $this->createMock(SettingRepositoryInterface::class);
        $this->violationConverterResolver = new ViolationConverterResolver($this->converterRegistry);
    }

    /**
     * @return void
     */
    public function testResolveWithNullConverterShouldReturnNull(): void
    {
        // Arrange
        $converter = null;
        $command = $this->tester->createCommand($converter);

        // Act
        $result = $this->violationConverterResolver->resolve($command);

        // Assert
        $this->assertNull($result);
    }

    /**
     * @return void
     */
    public function testResolveWithConverterRegistryHasNotConverter(): void
    {
        // Arrange
        $converter = new Converter('converter', []);
        $command = $this->tester->createCommand($converter);

        // Act
        $result = $this->violationConverterResolver->resolve($command);

        // Assert
        $this->assertNull($result);
    }

    /**
     * @return void
     */
    public function testResolveWithConverterRegistryHasConverterButCannotRetrieveViolationConverter(): void
    {
        // Arrange
        $converter = new Converter('converter', []);
        $command = $this->tester->createCommand($converter);

        $this->converterRegistry
            ->expects($this->once())
            ->method('has')
            ->willReturn(true);

        $this->converterRegistry
            ->expects($this->once())
            ->method('get')
            ->willReturn(null);

        // Act
        $result = $this->violationConverterResolver->resolve($command);

        // Assert
        $this->assertNull($result);
    }

    /**
     * @return void
     */
    public function testResolveShouldReturnViolationConverter(): void
    {
        // Arrange
        $converter = new Converter('converter', []);
        $command = $this->tester->createCommand($converter);

        $violationConverter = $this->createMock(ViolationConverterInterface::class);
        $violationConverter
            ->expects($this->once())
            ->method('configure')
            ->with($converter->getConfiguration());

        $this->converterRegistry
            ->expects($this->once())
            ->method('has')
            ->willReturn(true);

        $this->converterRegistry
            ->expects($this->once())
            ->method('get')
            ->willReturn($violationConverter);

        // Act
        $result = $this->violationConverterResolver->resolve($command);

        // Assert
        $this->assertSame($violationConverter, $result);
    }
}
