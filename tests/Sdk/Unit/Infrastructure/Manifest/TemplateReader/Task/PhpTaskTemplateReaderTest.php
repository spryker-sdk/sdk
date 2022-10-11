<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Manifest\TemplateReader\Task;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestFile;
use SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestRequestDtoInterface;
use SprykerSdk\Sdk\Infrastructure\Manifest\TemplateReader\Task\PhpTaskTemplateReader;
use Twig\Environment;

/**
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Manifest
 * @group TemplateReader
 * @group Task
 * @group PhpTaskTemplateReaderTest
 */
class PhpTaskTemplateReaderTest extends Unit
{
    /**
     * @return void
     */
    public function testRenderTemplateShouldRenderData(): void
    {
        // Arrange
        $templateReader = new PhpTaskTemplateReader($this->createTwigMock(), 'some_dir');
        $manifestFile = new ManifestFile('php', '');
        $requestDto = $this->createManifestRequestDto($manifestFile);

        // Act
        $templateReader->readTemplate($requestDto);
    }

    /**
     * @return \Twig\Environment
     */
    public function createTwigMock(): Environment
    {
        $twigMock = $this->createMock(Environment::class);
        $twigMock->expects($this->once())->method('render');

        return $twigMock;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestFile $manifestFile
     *
     * @return \SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestRequestDtoInterface
     */
    protected function createManifestRequestDto(ManifestFile $manifestFile): ManifestRequestDtoInterface
    {
        $manifestRequestDtoInterface = $this->createMock(ManifestRequestDtoInterface::class);
        $manifestRequestDtoInterface
            ->method('getManifestFile')
            ->willReturn($manifestFile);

        return $manifestRequestDtoInterface;
    }
}
