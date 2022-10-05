<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Core\Application\Service\ManifestValidation;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\ManifestConfigurationInterface;
use SprykerSdk\Sdk\Core\Application\Exception\ManifestValidatorMissingException;
use SprykerSdk\Sdk\Core\Application\Service\ManifestNormalizer\ManifestNormaliserRegistry;

/**
 * @group Sdk
 * @group Core
 * @group Application
 * @group Service
 * @group ManifestValidation
 * @group ManifestValidatorFactoryTest
 */
class ManifestNormalizerRegistryTest extends Unit
{
    /**
     * @return void
     */
    public function testResolve(): void
    {
        // Arrange
        $manifestValidator = $this->createMock(ManifestConfigurationInterface::class);
        $manifestValidator->expects($this->once())
            ->method('getName')
            ->willReturn('task');

        $manifestValidatorFactory = new ManifestNormaliserRegistry([$manifestValidator]);

        // Act
        $result = $manifestValidatorFactory->resolve('task');

        // Assert
        $this->assertSame($manifestValidator, $result);
    }

    /**
     * @return void
     */
    public function testCanNotResolve(): void
    {
        // Arrange
        $manifestValidator = $this->createMock(ManifestConfigurationInterface::class);
        $manifestValidator->expects($this->once())
            ->method('getName')
            ->willReturn('task');

        $manifestValidatorFactory = new ManifestNormaliserRegistry([$manifestValidator]);

        // Assert
        $this->expectException(ManifestValidatorMissingException::class);

        // Act
        $result = $manifestValidatorFactory->resolve('none');
    }
}
