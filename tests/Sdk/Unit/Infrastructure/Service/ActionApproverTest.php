<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Service\ActionApprover;
use SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\InteractionProcessor;

class ActionApproverTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\InteractionProcessor
     */
    protected InteractionProcessor $cliValueReceiver;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->cliValueReceiver = $this->createMock(InteractionProcessor::class);

        parent::setUp();
    }

    /**
     * @return void
     */
    public function testApprove(): void
    {
        // Arrange
        $this->cliValueReceiver
            ->expects($this->once())
            ->method('receiveValue')
            ->willReturn(true);
        $actionApprover = new ActionApprover($this->cliValueReceiver);

        // Act
        $result = $actionApprover->approve('message');

        // Assert
        $this->assertTrue($result);
    }
}
