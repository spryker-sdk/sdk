<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\ValueResolvers;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Extension\ValueResolvers\FlagValueResolver;
use SprykerSdk\SdkContracts\ValueReceiver\ValueReceiverInterface;

class StaticValueResolverTest extends Unit
{
    /**
     * @var \SprykerSdk\SdkContracts\ValueReceiver\ValueReceiverInterface
     */
    protected ValueReceiverInterface $valueReceiver;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->valueReceiver = $this->createMock(ValueReceiverInterface::class);

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
            'type' => 'string',
            'settingPaths' => ['setting'],
            'choiceValues' => ['values'],
        ];
        $valueResolver = new FlagValueResolver($this->valueReceiver);
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
}
