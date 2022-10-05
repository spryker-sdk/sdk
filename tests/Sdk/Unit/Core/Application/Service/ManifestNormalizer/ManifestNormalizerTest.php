<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Core\Application\Service\ManifestValidation;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\ManifestConfigurationInterface;
use SprykerSdk\Sdk\Core\Application\Exception\ManifestValidatorMissingException;
use SprykerSdk\Sdk\Core\Application\Service\ManifestValidator\ManifestValidator;
use SprykerSdk\Sdk\Core\Application\Service\ManifestValidator\ManifestValidatorRegistry;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\NodeInterface;
use Symfony\Component\Config\Definition\Processor;

/**
 * @group Sdk
 * @group Core
 * @group Application
 * @group Service
 * @group ManifestValidation
 * @group ManifestValidatorTest
 */
class ManifestNormalizerTest extends Unit
{
    /**
     * @return void
     */
    public function testValidate(): void
    {
        // Arrange
        $data = ['path' => ['test']];
        $entity = 'task';
        $node = $this->createMock(NodeInterface::class);
        $treeBuilder = $this->createMock(TreeBuilder::class);
        $treeBuilder->expects($this->once())
            ->method('buildTree')
            ->willReturn($node);
        $manifestValidator = $this->createMock(ManifestConfigurationInterface::class);
        $manifestValidator->expects($this->once())
            ->method('getConfigTreeBuilder')
            ->willReturn($treeBuilder);
        $manifestValidatorFactory = $this->createMock(ManifestValidatorRegistry::class);
        $manifestValidatorFactory->expects($this->once())
            ->method('resolve')
            ->with($entity)
            ->willReturn($manifestValidator);
        $processor = $this->createMock(Processor::class);
        $processor->expects($this->once())
            ->method('process')
            ->with($node, [['test']])
            ->willReturn(['test']);

        $manifestValidation = new ManifestValidator($manifestValidatorFactory, $processor);

        // Act
        $result = $manifestValidation->validate('task', $data);

        // Assert
        $this->assertSame($data, $result);
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

        $manifestValidatorFactory = new ManifestValidatorRegistry([$manifestValidator]);

        // Assert
        $this->expectException(ManifestValidatorMissingException::class);

        // Act
        $result = $manifestValidatorFactory->resolve('none');
    }
}
