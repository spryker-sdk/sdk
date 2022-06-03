<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Reader;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Infrastructure\Reader\WorkflowYamlReader;
use SprykerSdk\Sdk\Infrastructure\Repository\SettingRepository;
use SprykerSdk\Sdk\Tests\UnitTester;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class WorkflowYamlReaderTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Reader\WorkflowYamlReader
     */
    protected WorkflowYamlReader $workflowYamlReader;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface&\PHPUnit\Framework\MockObject\MockObject
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @var \Symfony\Component\Finder\Finder&\PHPUnit\Framework\MockObject\MockObject
     */
    protected Finder $fileFinder;

    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected Yaml $yamlParser;

    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @var string
     */
    protected string $workflowPath = 'tests/_support/data/Workflows/DefaultWorkflow.yaml';

    /**
     * @return void
     */
    public function testLoadWorkflowsWithOverridingFromExternalDefinition(): void
    {
        // Arrange
        $setting = $this->tester->createInfrastructureSetting(
            'workflow_dirs',
            [realpath(__DIR__ . '/../../../../_support')],
        );

        $this->settingRepository
            ->expects($this->exactly(2))
            ->method('findOneByPath')
            ->with('workflow_dirs')
            ->willReturn($setting);

        // Act
        $result = $this->workflowYamlReader->loadWorkflows();

        // Assert
        $this->assertEquals(
            [
                'type' => 'workflow',
                'overriden_by' => 'AnotherOverridingWorkflow:framework:workflows:another_workflow_test',
                'marking_store' => [
                    'type' => 'method',
                    'property' => 'status',
                ],
                'metadata' => [
                    'run' => 'single',
                ],
                'supports' => [
                    'SprykerSdk\SdkContracts\Entity\WorkflowInterface',
                ],
                'initial_marking' => 'codestyle',
                'places' => [
                    'changed_to_a',
                    'changed_to_b',
                    'changed_to_another_c',
                ],
                'transitions' => [
                    'check' => [
                        'from' => 'changed_to_a',
                        'to' => 'changed_to_another_c',
                        'metadata' => [
                            'task' => 'changed_to_test',
                        ],
                    ],
                    'fix' => [
                        'from' => 'codestyle',
                        'to' => 'phstan-check',
                        'metadata' => [
                            'task' => 'validation:php:static',
                        ],
                    ],
                    'phpstan' => [
                        'from' => 'phstan-check',
                        'to' => 'done',
                        'metadata' => [
                            'task' => 'validation:php:static',
                        ],
                    ],
                ],
            ],
            $result['default'],
        );

        $this->assertEquals(
            [
                'type' => 'workflow',
                'overriden_by' => 'workflow_test',
                'marking_store' => [
                    'type' => 'method',
                    'property' => 'status',
                ],
                'supports' => [
                    'SprykerSdk\SdkContracts\Entity\WorkflowInterface',
                ],
                'initial_marking' => 'changed_to_manifest_test',
                'places' => [
                    'changed_to_start_test',
                    'changed_to_pbc-skeleton_test',
                    'changed_to_manifest_test',
                    'changed_to_config_test',
                    'changed_to_translation_test',
                    'changed_to_acp-validated_test',
                    'changed_to_openapi_test',
                    'changed_to_openapi-validated_test',
                    'changed_to_asyncapi_test',
                    'asyncapi-validated',
                    'asyncapi-generate',
                    'pbc-validate',
                    'pbc-validated',
                ],
                'transitions' => [
                    'CreatePbcSkeleton' => [
                        'from' => 'changed_to_start_test',
                        'to' => 'pbc-skeleton',
                        'metadata' => [
                            'task' => 'generate:php:pbc',
                        ],
                    ],
                    'CreateManifest' => [
                        'from' => 'pbc-skeleton',
                        'to' => 'manifest',
                        'metadata' => [
                            'task' => 'acp:manifest:create',
                        ],
                    ],
                ],
            ],
            $result['pbc'],
        );
    }

    /**
     * @return void
     */
    public function testWorkflowFileDoesnotExist(): void
    {
        // Arrange
        $this->workflowYamlReader = new WorkflowYamlReader(
            $this->settingRepository,
            new Finder(),
            new Yaml(),
            'notExist/',
        );

        $this->settingRepository
            ->expects($this->never())
            ->method('findOneByPath');

        // Assert
        $this->expectException(DirectoryNotFoundException::class);
        $this->expectExceptionMessage('The "notExist" directory does not exist.');

        // Act
        $this->workflowYamlReader->loadWorkflows();
    }

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->settingRepository = $this->createMock(SettingRepository::class);
        $this->fileFinder = $this->createMock(Finder::class);
        $this->workflowYamlReader = new WorkflowYamlReader(
            $this->settingRepository,
            new Finder(),
            new Yaml(),
            $this->workflowPath,
        );
    }
}
