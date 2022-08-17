<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Repository;

use Codeception\Test\Unit;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use SprykerSdk\Sdk\Core\Application\Cache\ContextCacheStorageInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Service\ContextSerializer;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Infrastructure\Exception\MissingContextFileException;
use SprykerSdk\Sdk\Infrastructure\Repository\ContextFileRepository;
use SprykerSdk\Sdk\Tests\UnitTester;

class ContextFileRepositoryTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Repository\ContextFileRepository
     */
    protected ContextFileRepository $contextFileRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Service\ContextSerializer
     */
    protected ContextSerializer $contextSerializer;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    protected vfsStreamDirectory $vfsStream;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Cache\ContextCacheStorageInterface
     */
    protected ContextCacheStorageInterface $contextCacheStorage;

    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->contextSerializer = $this->createMock(ContextSerializer::class);
        $this->settingRepository = $this->createMock(SettingRepositoryInterface::class);
        $this->contextCacheStorage = $this->createMock(ContextCacheStorageInterface::class);

        $this->vfsStream = vfsStream::setup();
        $this->contextFileRepository = new ContextFileRepository(
            $this->contextSerializer,
            $this->settingRepository,
            $this->contextCacheStorage,
        );
    }

    /**
     * @return void
     */
    public function testSaveContextShouldSaveJsonToFile(): void
    {
        // Arrange
        $context = $this->tester->createContext();
        $jsonContext = json_encode($this->tester->createArrayContext());
        $setting = $this->tester->createSetting('project_dir', $this->vfsStream->url());

        $this->settingRepository
            ->expects($this->once())
            ->method('getOneByPath')
            ->with('project_dir')
            ->willReturn($setting);

        $this->contextSerializer
            ->expects($this->once())
            ->method('serialize')
            ->willReturn($jsonContext);

        // Act
        $result = $this->contextFileRepository->saveContext($context);

        // Assert
        $this->assertSame($context, $result);

        $childName = $context->getName() . '.context.json';

        $this->assertTrue($this->vfsStream->hasChild($childName));
        $this->assertSame($jsonContext, $this->vfsStream->getChild($childName)->getContent());
    }

    /**
     * @return void
     */
    public function testDeleteShouldDeleteFileFromVfs(): void
    {
        // Arrange
        $jsonContext = json_encode($this->tester->createArrayContext());
        $context = $this->tester->createContext();

        $fileName = $context->getName() . '.context.json';

        $vfsFile = $this->tester->createVfsStreamFile($fileName, $jsonContext);
        $this->vfsStream->addChild($vfsFile);

        $setting = $this->tester->createSetting('project_dir', $this->vfsStream->url());

        $this->settingRepository
            ->expects($this->once())
            ->method('getOneByPath')
            ->with('project_dir')
            ->willReturn($setting);

        // Act
        $this->contextFileRepository->delete($context);

        // Assert
        $this->assertFalse($this->vfsStream->hasChild($fileName));
    }

    /**
     * @return void
     */
    public function testFindByNameShouldReturnContext(): void
    {
        // Arrange
        $jsonContext = json_encode($this->tester->createArrayContext());
        $context = $this->tester->createContext();

        $fileName = $context->getName() . '.context.json';
        $vfsFile = $this->tester->createVfsStreamFile($fileName, $jsonContext);
        $this->vfsStream->addChild($vfsFile);

        $setting = $this->tester->createSetting('project_dir', $this->vfsStream->url());

        $this->settingRepository
            ->expects($this->once())
            ->method('getOneByPath')
            ->with('project_dir')
            ->willReturn($setting);

        $this->contextSerializer
            ->expects($this->once())
            ->method('deserialize')
            ->willReturn($context);

        // Act
        $result = $this->contextFileRepository->findByName($context->getName());

        // Assert
        $this->assertSame($context, $result);
    }

    /**
     * @return void
     */
    public function testFindByNameWithEmptyContextShouldThrowException(): void
    {
        // Arrange
        $context = $this->tester->createContext();

        $fileName = $context->getName() . '.context.json';
        $vfsFile = $this->tester->createVfsStreamFile($fileName, '');
        $this->vfsStream->addChild($vfsFile);

        $setting = $this->tester->createSetting('project_dir', $this->vfsStream->url());

        $this->settingRepository
            ->expects($this->once())
            ->method('getOneByPath')
            ->with('project_dir')
            ->willReturn($setting);

        $this->expectException(MissingContextFileException::class);

        // Act
        $this->contextFileRepository->findByName($context->getName());
    }

    /**
     * @return void
     */
    public function testFindByNameWithoutFileInVfsShouldThrowException(): void
    {
        // Arrange
        $context = $this->tester->createContext();

        $setting = $this->tester->createSetting('project_dir', $this->vfsStream->url());

        $this->settingRepository
            ->expects($this->once())
            ->method('getOneByPath')
            ->with('project_dir')
            ->willReturn($setting);

        $this->expectException(MissingContextFileException::class);

        // Act
        $this->contextFileRepository->findByName($context->getName());
    }

    /**
     * @return void
     */
    public function testFindByNameReturnsCachedContextIfExists(): void
    {
        // Arrange
        $expectedContext = $this->tester->createContext();
        $this->contextCacheStorage
            ->expects($this->once())
            ->method('get')
            ->with($expectedContext->getName())
            ->willReturn($expectedContext);

        // Act
        $actualContext = $this->contextFileRepository->findByName($expectedContext->getName());

        // Assert
        $this->assertSame($expectedContext, $actualContext);
    }

    /**
     * @return void
     */
    public function testGetLastSavedContextReturnsContextIfExists(): void
    {
        // Arrange
        $expectedContext = $this->tester->createContext();
        $this->contextCacheStorage
            ->expects($this->once())
            ->method('get')
            ->with(ContextCacheStorageInterface::KEY_LAST)
            ->willReturn($expectedContext);

        // Act
        $context = $this->contextFileRepository->getLastSavedContextOrNew();

        // Assert
        $this->assertSame($expectedContext, $context);
    }

    /**
     * @return void
     */
    public function testGetLastSavedContextReturnsNewContextIfItNotExists(): void
    {
        // Arrange
        $this->contextCacheStorage
            ->expects($this->once())
            ->method('get')
            ->with(ContextCacheStorageInterface::KEY_LAST)
            ->willReturn(null);

        // Act
        $context = $this->contextFileRepository->getLastSavedContextOrNew();

        // Assert
        $this->assertEquals(new Context(), $context);
    }
}
