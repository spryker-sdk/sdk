<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\ValueResolver;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Extension\ValueResolver\OriginValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;

/**
 * @group Sdk
 * @group Extension
 * @group ValueResolver
 * @group OriginValueResolverTest
 */
class OriginValueResolverTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface
     */
    protected InteractionProcessorInterface $valueReceiver;

    /**
     * @var \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected ContextInterface $context;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->valueReceiver = $this->createMock(InteractionProcessorInterface::class);

        $this->context = $this->createMock(ContextInterface::class);

        parent::setUp();
    }

    /**
     * @return void
     */
    public function testConfigure(): void
    {
        // Arrange
        $values = [
            'defaultValue' => 'defaultValue',
            'name' => 'alias',
            'description' => 'description',
            'help' => 'help',
            'type' => ValueTypeEnum::TYPE_BOOL,
            'settingPaths' => ['setting'],
            'choiceValues' => ['values'],
        ];
        $valueResolver = new OriginValueResolver($this->valueReceiver);
        $valueResolver->configure($values);

        // Assert
        $this->assertSame($values['defaultValue'], $valueResolver->getDefaultValue());
        $this->assertSame($values['name'], $valueResolver->getAlias());
        $this->assertSame($values['description'], $valueResolver->getDescription());
        $this->assertSame($values['help'], $valueResolver->getHelp());
        $this->assertSame($values['type'], $valueResolver->getType());
        $this->assertSame($values['type'], $valueResolver->getType());
        $this->assertSame($values['settingPaths'], $valueResolver->getSettingPaths());
        $this->assertSame($values['choiceValues'], $valueResolver->getChoiceValues([]));
    }

    /**
     * @return void
     */
    public function testGetValue(): void
    {
        // Arrange
        $this->valueReceiver
            ->expects($this->once())
            ->method('has')
            ->willReturn(true);
        $this->valueReceiver
            ->expects($this->once())
            ->method('get')
            ->willReturn('value');
        $valueResolver = new OriginValueResolver($this->valueReceiver);
        $valueResolver->configure(['option' => 'test', 'name' => 'key', 'description' => '']);
        // Act
        $value = $valueResolver->getValue($this->context, ['defaultValue' => 'value']);

        // Assert
        $this->assertSame('value', $value);
    }
}
