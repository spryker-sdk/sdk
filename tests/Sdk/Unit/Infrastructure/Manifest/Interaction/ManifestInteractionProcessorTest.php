<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Manifest\Interaction;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue as Config;
use SprykerSdk\Sdk\Core\Domain\Enum\ValueTypeEnum;
use SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\CallbackValue;
use SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\InteractionValueConfig;
use SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\ReceivedValue;
use SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\StaticValue;
use SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\ValueCollection;
use SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\ManifestInteractionProcessor;
use SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Question\NeedToAskQuestion;
use SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Question\NewCollectionItemQuestion;
use SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Question\ValueQuestion;

/**
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Manifest
 * @group Interaction
 * @group ManifestInteractionProcessorTest
 */
class ManifestInteractionProcessorTest extends Unit
{
    /**
     * @return void
     */
    public function testReceiveValuesShouldReturnNonSetValueWhenValueNotRequired(): void
    {
        // Arrange
        $map = [
            'id' => new ReceivedValue(
                new Config('Task id', null, ValueTypeEnum::TYPE_STRING),
                false,
            ),
        ];

        $manifestInteractionProcessor = new ManifestInteractionProcessor(
            $this->createNeedToAskQuestionMock(false),
            $this->createValueQuestionMock(),
            $this->createNewCollectionItemQuestionMock(),
        );

        // Act
        $receivedValues = $manifestInteractionProcessor->receiveValues($map);

        // Assert
        $this->assertSame([], $receivedValues);
    }

    /**
     * @return void
     */
    public function testReceiveValuesShouldReturnValueWhenValueRequired(): void
    {
        // Arrange
        $map = [
            'id' => new ReceivedValue(
                new Config('Task id', null, ValueTypeEnum::TYPE_STRING),
                true,
            ),
        ];

        $manifestInteractionProcessor = new ManifestInteractionProcessor(
            $this->createNeedToAskQuestionMock(),
            $this->createValueQuestionMock(['test_id']),
            $this->createNewCollectionItemQuestionMock(),
        );

        // Act
        $receivedValues = $manifestInteractionProcessor->receiveValues($map);

        // Assert
        $this->assertSame(['id' => 'test_id'], $receivedValues);
    }

    /**
     * @return void
     */
    public function testReceiveValuesShouldReturnEmptyWhenCollectionItemsNotRequired(): void
    {
        // Arrange
        $map = [
            'items' => new ValueCollection(
                [
                    new ReceivedValue(new Config('item id', null, ValueTypeEnum::TYPE_STRING)),
                ],
                false,
            ),
        ];

        $manifestInteractionProcessor = new ManifestInteractionProcessor(
            $this->createNeedToAskQuestionMock(),
            $this->createValueQuestionMock(),
            $this->createNewCollectionItemQuestionMock([false]),
        );

        // Act
        $receivedValues = $manifestInteractionProcessor->receiveValues($map);

        // Assert
        $this->assertSame([], $receivedValues);
    }

    /**
     * @return void
     */
    public function testReceiveValuesShouldReturnCollectionWhenCollectionItemRequired(): void
    {
        // Arrange
        $map = [
            'items' => new ValueCollection(
                [
                    'id' => new ReceivedValue(new Config('item id', null, ValueTypeEnum::TYPE_STRING)),
                    'name' => new ReceivedValue(new Config('item name', null, ValueTypeEnum::TYPE_STRING)),
                ],
                true,
            ),
        ];

        $manifestInteractionProcessor = new ManifestInteractionProcessor(
            $this->createNeedToAskQuestionMock(),
            $this->createValueQuestionMock(['itemIdOne', 'itemNameOne', 'itemIdTwo', 'itemNameTwo']),
            $this->createNewCollectionItemQuestionMock([true, false]),
        );

        // Act
        $receivedValues = $manifestInteractionProcessor->receiveValues($map);

        // Assert
        $this->assertSame(
            [
                'items' => [
                    ['id' => 'itemIdOne', 'name' => 'itemNameOne'],
                    ['id' => 'itemIdTwo', 'name' => 'itemNameTwo'],
                ],
            ],
            $receivedValues,
        );
    }

    /**
     * @return void
     */
    public function testReceiveValuesShouldReturnNestedArrayWhenNestedConfigSet(): void
    {
        // Arrange
        $map = [
            'item' => [
                'levelOne' => [
                    'levelTwo' => new ReceivedValue(new Config('item id', null, ValueTypeEnum::TYPE_STRING)),
                ],
            ],
        ];

        $manifestInteractionProcessor = new ManifestInteractionProcessor(
            $this->createNeedToAskQuestionMock(),
            $this->createValueQuestionMock(['testValue']),
            $this->createNewCollectionItemQuestionMock(),
        );

        // Act
        $receivedValues = $manifestInteractionProcessor->receiveValues($map);

        // Assert
        $this->assertSame(['item' => ['levelOne' => ['levelTwo' => 'testValue']]], $receivedValues);
    }

    /**
     * @return void
     */
    public function testReceiveValuesShouldReturnStaticWhenStaticValueSet(): void
    {
        // Arrange
        $map = [
            'item' => new StaticValue('testValue'),
        ];

        $manifestInteractionProcessor = new ManifestInteractionProcessor(
            $this->createNeedToAskQuestionMock(false),
            $this->createValueQuestionMock(['testValue']),
            $this->createNewCollectionItemQuestionMock(),
        );

        // Act
        $receivedValues = $manifestInteractionProcessor->receiveValues($map);

        // Assert
        $this->assertSame(['item' => 'testValue'], $receivedValues);
    }

    /**
     * @return void
     */
    public function testReceiveValuesShouldReturnPreProcessedWhenCallbackValueSet(): void
    {
        // Arrange
        $map = [
            'id' => new ReceivedValue(
                new Config('Task id', null, ValueTypeEnum::TYPE_STRING),
            ),
            'prefixed_id' => new CallbackValue(static function (array $receivedValues): InteractionValueConfig {
                return new StaticValue('some_prefix_' . $receivedValues['id']);
            }),
        ];

        $manifestInteractionProcessor = new ManifestInteractionProcessor(
            $this->createNeedToAskQuestionMock(),
            $this->createValueQuestionMock(['test_id']),
            $this->createNewCollectionItemQuestionMock(),
        );

        // Act
        $receivedValues = $manifestInteractionProcessor->receiveValues($map);

        // Assert
        $this->assertSame(['id' => 'test_id', 'prefixed_id' => 'some_prefix_test_id'], $receivedValues);
    }

    /**
     * @param bool $returnValue
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Question\NeedToAskQuestion
     */
    protected function createNeedToAskQuestionMock(bool $returnValue = true): NeedToAskQuestion
    {
        $needToAskQuestionMock = $this->createMock(NeedToAskQuestion::class);
        $needToAskQuestionMock->method('ask')->willReturn($returnValue);

        return $needToAskQuestionMock;
    }

    /**
     * @param array<bool> $returnValues
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Question\NewCollectionItemQuestion
     */
    protected function createNewCollectionItemQuestionMock(array $returnValues = []): NewCollectionItemQuestion
    {
        $needToAskQuestionMock = $this->createMock(NewCollectionItemQuestion::class);
        $needToAskQuestionMock
            ->method('ask')
            ->willReturnOnConsecutiveCalls(...$returnValues);

        return $needToAskQuestionMock;
    }

    /**
     * @param array $returnValues
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Question\ValueQuestion
     */
    protected function createValueQuestionMock(array $returnValues = []): ValueQuestion
    {
        $needToAskQuestionMock = $this->createMock(ValueQuestion::class);
        $needToAskQuestionMock
            ->method('ask')
            ->willReturnOnConsecutiveCalls(...$returnValues);

        return $needToAskQuestionMock;
    }
}
