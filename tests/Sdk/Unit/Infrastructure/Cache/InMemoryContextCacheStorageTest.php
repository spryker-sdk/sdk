<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Cache;

use Codeception\Test\Unit;
use phpDocumentor\Reflection\Types\Context;
use SprykerSdk\Sdk\Infrastructure\Cache\InMemoryContextCacheStorage;
use SprykerSdk\Sdk\Infrastructure\Service\ActionApprover;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver;
use SprykerSdk\Sdk\Infrastructure\Service\EventLogger;
use SprykerSdk\Sdk\Infrastructure\Service\EventLoggerFactory;
use SprykerSdk\SdkContracts\Entity\SettingInterface;

class InMemoryContextCacheStorageTest extends Unit
{
    public function testSet(): void
    {
        // Arrange
        $cacheStorage = new InMemoryContextCacheStorage();
        $context = new Context();

        // Act
        $cacheStorage->set();

        // Assert
    }
}
