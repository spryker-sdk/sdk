<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Reader;

use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException;
use SprykerSdk\Sdk\Core\Appplication\Reader\WorkflowReaderInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class WorkflowYamlReader implements WorkflowReaderInterface
{
    /**
     * @var string
     */
    protected const OVERRIDEN_BY = 'overriden_by';

    /**
     * @var string
     */
    protected const WORKFLOW_DIRS = 'workflow_dirs';

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected Finder $fileFinder;

    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected Yaml $yamlParser;

    /**
     * @var array
     */
    protected array $workflows = [];

    /**
     * @var string
     */
    protected string $workflowFile;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface $settingRepository
     * @param \Symfony\Component\Finder\Finder $fileFinder
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     * @param string $workflowFile
     */
    public function __construct(
        SettingRepositoryInterface $settingRepository,
        Finder $fileFinder,
        Yaml $yamlParser,
        string $workflowFile
    ) {
        $this->settingRepository = $settingRepository;
        $this->yamlParser = $yamlParser;
        $this->fileFinder = $fileFinder;
        $this->workflowFile = $workflowFile;
    }

    /**
     * @return array
     */
    public function loadWorkflows(): array
    {
        if (count($this->workflows) !== 0) {
            return $this->workflows;
        }

        $workflows = [];
        $workflowFilePath = explode('/', $this->workflowFile);
        $workflowFileName = array_pop($workflowFilePath);

        $finder = $this->fileFinder
            ->in(implode('/', $workflowFilePath))
            ->name($workflowFileName);

        foreach ($finder->files() as $workflowFile) {
            $workflowsData = $this->yamlParser->parse($workflowFile->getContents());
            $workflows = $workflowsData['framework']['workflows'] ?? [];
        }

        return $this->buildWorkflow($workflows);
    }

    /**
     * @param array $workflows
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException
     *
     * @return array
     */
    protected function buildWorkflow(array $workflows): array
    {
        foreach ($workflows as $workflowName => $workflow) {
            if (isset($workflow[static::OVERRIDEN_BY])) {
                $externalWorkflowDefinition = $workflow[static::OVERRIDEN_BY];
                $externalWorkflow = [];

                if (strpos($externalWorkflowDefinition, ':')) {
                    [$externalWorkflowFile, $externalWorkflowFrameworkKey, $externalWorkflowWorkflowKey, $externalWorkflowWorkflowName]
                        = explode(':', $externalWorkflowDefinition);

                    $workflowDirSetting = $this->settingRepository->findOneByPath(static::WORKFLOW_DIRS);

                    if (!$workflowDirSetting || !is_array($workflowDirSetting->getValues())) {
                        throw new MissingSettingException('workflow_dirs is not configured properly');
                    }

                    $finder = $this->fileFinder::create()
                        ->in(array_map(fn (string $directory): string => $directory . '/*/Workflows/', $workflowDirSetting->getValues()))
                        ->name($externalWorkflowFile . '.yaml');

                    foreach ($finder->files() as $workflowFile) {
                        $workflowsData = $this->yamlParser->parse($workflowFile->getContents());
                        $externalWorkflow[$workflowName] = $workflowsData[$externalWorkflowFrameworkKey][$externalWorkflowWorkflowKey][$externalWorkflowWorkflowName] ?? [];
                    }
                } else {
                    $externalWorkflow[$workflowName] = $workflows[$externalWorkflowDefinition];
                }

                $externalWorkflow = $this->buildWorkflow($externalWorkflow);
                $workflow = $this->mergeArraysRecursively($workflow, $externalWorkflow[$workflowName]);
            }

            $workflows[$workflowName] = $workflow;
        }

        return $workflows;
    }

    /**
     * @param array $workflow
     * @param array $externalWorkflow
     *
     * @return array
     */
    protected function mergeArraysRecursively(array $workflow, array $externalWorkflow): array
    {
        foreach ($externalWorkflow as $key => $value) {
            if (isset($workflow[$key])) {
                if (is_array($workflow[$key]) && is_array($value)) {
                    $workflow[$key] = $this->mergeArraysRecursively($workflow[$key], $value);

                    continue;
                }

                $workflow[$key] = $value;
            }
        }

        return $workflow;
    }
}
