<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Validator\Manifest;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\ManifestConfigurationInterface;
use SprykerSdk\Sdk\Core\Application\Exception\ManifestValidatorMissingException;
use SprykerSdk\Sdk\Infrastructure\Validator\Manifest\ManifestValidatorRegistry;

/**
 * @group Unit
 * @group Infrastructure
 * @group Validator
 * @group Manifest
 * @group ManifestValidatorRegistryTest
 */
class ManifestValidatorRegistryTest extends Unit
{
    /**
     * @return void
     */
    public function testGetValidator(): void
    {
        // Arrange
        $manifestValidator = $this->createMock(ManifestConfigurationInterface::class);
        $manifestValidator->expects($this->once())
            ->method('getName')
            ->willReturn('task');

        $manifestValidatorFactory = new ManifestValidatorRegistry([$manifestValidator]);

        // Act
        $result = $manifestValidatorFactory->getValidator('task');

        // Assert
        $this->assertSame($manifestValidator, $result);
    }

    /**
     * @return void
     */
    public function testGetValidatorWithException(): void
    {
        // Arrange
        $manifestValidator = $this->createMock(ManifestConfigurationInterface::class);
        $manifestValidator->expects($this->once())
            ->method('getName')
            ->willReturn('task');

        $manifestValidatorFactory = new ManifestValidatorRegistry([$manifestValidator]);

        // Assert
        $this->expectException(ManifestValidatorMissingException::class);

        // Act
        $result = $manifestValidatorFactory->getValidator('none');
    }
}
