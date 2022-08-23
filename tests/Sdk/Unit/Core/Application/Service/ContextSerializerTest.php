<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Core\Application\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface;
use SprykerSdk\Sdk\Core\Application\Service\ContextSerializer;
use SprykerSdk\Sdk\Core\Application\Service\ReportArrayConverterFactory;
use SprykerSdk\Sdk\Core\Application\Service\Violation\ViolationReportArrayConverter;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Tests\UnitTester;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;

/**
 * @group Sdk
 * @group Core
 * @group Application
 * @group Service
 * @group ContextSerializerTest
 */
class ContextSerializerTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Service\ContextSerializer
     */
    protected ContextSerializer $contextSerializer;

    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface
     */
    protected ContextFactoryInterface $contextFactory;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->contextFactory = $this->createMock(ContextFactoryInterface::class);
        $this->contextFactory
            ->method('getContext')
            ->willReturn(new Context());
        $this->contextSerializer = new ContextSerializer(
            new ReportArrayConverterFactory([new ViolationReportArrayConverter()]),
            $this->contextFactory,
        );
    }

    /**
     * @return void
     */
    public function testSerializeShouldReturnValidJson(): void
    {
        // Arrange
        $expectedArrayContext = $this->tester->createArrayContext();

        $context = $this->tester->createContext(
            $expectedArrayContext['resolved_values'],
            $expectedArrayContext['tags'],
            $expectedArrayContext['messages'],
            $expectedArrayContext['reports'],
        );

        $expectedJson = json_encode($expectedArrayContext);

        // Act
        $resultJson = $this->contextSerializer->serialize($context);

        // Assert
        $this->assertJson($resultJson);
        $this->assertSame($expectedJson, $resultJson);
    }

    /**
     * @return void
     */
    public function testDeserializeShouldReturnContext(): void
    {
        // Arrange
        $arrayContext = $this->tester->createArrayContext();

        $expectedContext = $this->tester->createContext(
            $arrayContext['resolved_values'],
            $arrayContext['tags'],
            $arrayContext['messages'],
            $arrayContext['reports'],
        );

        $jsonContext = json_encode($arrayContext);

        // Act
        $resultContext = $this->contextSerializer->deserialize($jsonContext);

        // Assert
        $this->assertInstanceOf(ContextInterface::class, $resultContext);
        $this->assertEquals($expectedContext, $resultContext);
    }

    /**
     * @return void
     */
    public function testDeserializeWithoutEmptyMessagesShouldReturnContext(): void
    {
        // Arrange
        $arrayContext = $this->tester->createArrayContext([
            'key1' => [
                'message' => '',
                'verbosity' => MessageInterface::ERROR,
            ],
        ]);

        $expectedContext = $this->tester->createContext(
            $arrayContext['resolved_values'],
            $arrayContext['tags'],
            [],
            $arrayContext['reports'],
        );

        $jsonContext = json_encode($arrayContext);

        // Act
        $resultContext = $this->contextSerializer->deserialize($jsonContext);

        // Assert
        $this->assertInstanceOf(ContextInterface::class, $resultContext);
        $this->assertEquals($expectedContext, $resultContext);
        $this->assertEmpty($resultContext->getMessages());
    }
}
