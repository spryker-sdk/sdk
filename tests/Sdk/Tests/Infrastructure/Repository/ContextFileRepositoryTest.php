<?php

namespace Sdk\Tests\Infrastructure\Repository;

use Codeception\Test\Unit;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\ContextSerializer;
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
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\ContextSerializer
     */
    protected ContextSerializer $contextSerializer;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    protected vfsStreamDirectory $vfsStream;

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

        $this->vfsStream = vfsStream::setup();
        $this->contextFileRepository = new ContextFileRepository($this->contextSerializer, $this->settingRepository);
    }

    /**
     * @return void
     */
    public function testSaveContextShouldSaveJsonToFile(): void
    {
        // Arrange
        $context = $this->tester->createContext();
        $jsonContext = json_encode($this->tester->createArrayContext());
        $setting = $this->tester->createSetting('context_dir', $this->vfsStream->url());

        $this->settingRepository
            ->expects($this->once())
            ->method('getOneByPath')
            ->with('context_dir')
            ->willReturn($setting);

        $this->contextSerializer
            ->expects($this->once())
            ->method('serialize')
            ->willReturn($jsonContext);

        // Act
        $result = $this->contextFileRepository->saveContext($context);

        // Assert
        $this->assertSame($context, $result);

        $childName = $context->getName().'.context.json';

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

        $fileName = $context->getName().'.context.json';

        $vfsFile = $this->tester->createVfsStreamFile($fileName, $jsonContext);
        $this->vfsStream->addChild($vfsFile);

        $setting = $this->tester->createSetting('context_dir', $this->vfsStream->url());

        $this->settingRepository
            ->expects($this->once())
            ->method('getOneByPath')
            ->with('context_dir')
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

        $fileName = $context->getName().'.context.json';
        $vfsFile = $this->tester->createVfsStreamFile($fileName, $jsonContext);
        $this->vfsStream->addChild($vfsFile);

        $setting = $this->tester->createSetting('context_dir', $this->vfsStream->url());

        $this->settingRepository
            ->expects($this->once())
            ->method('getOneByPath')
            ->with('context_dir')
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

        $fileName = $context->getName().'.context.json';
        $vfsFile = $this->tester->createVfsStreamFile($fileName, '');
        $this->vfsStream->addChild($vfsFile);

        $setting = $this->tester->createSetting('context_dir', $this->vfsStream->url());

        $this->settingRepository
            ->expects($this->once())
            ->method('getOneByPath')
            ->with('context_dir')
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

        $setting = $this->tester->createSetting('context_dir', $this->vfsStream->url());

        $this->settingRepository
            ->expects($this->once())
            ->method('getOneByPath')
            ->with('context_dir')
            ->willReturn($setting);

        $this->expectException(MissingContextFileException::class);

        // Act
        $this->contextFileRepository->findByName($context->getName());
    }
}
