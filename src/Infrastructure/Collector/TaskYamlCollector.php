<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Collector;

use SprykerSdk\Sdk\Core\Application\Dependency\ManifestValidatorInterface;
use SprykerSdk\Sdk\Infrastructure\Dto\ManifestCollectionDto;
use SprykerSdk\Sdk\Infrastructure\Reader\TaskYamlReader;
use SprykerSdk\Sdk\Infrastructure\Storage\TaskStorage;
use SprykerSdk\Sdk\Infrastructure\Validator\Manifest\TaskManifestConfiguration;
use SprykerSdk\Sdk\Infrastructure\Validator\Manifest\TaskSetManifestConfiguration;

class TaskYamlCollector implements TaskYamlCollectorInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ManifestValidatorInterface
     */
    protected ManifestValidatorInterface $manifestValidator;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Reader\TaskYamlReader
     */
    protected TaskYamlReader $taskYamlReader;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Storage\TaskStorage
     */
    protected TaskStorage $taskStorage;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Dto\ManifestCollectionDto
     */
    protected ManifestCollectionDto $collectionDto;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ManifestValidatorInterface $manifestValidator
     * @param \SprykerSdk\Sdk\Infrastructure\Reader\TaskYamlReader $taskYamlReader
     * @param \SprykerSdk\Sdk\Infrastructure\Storage\TaskStorage $taskStorage
     */
    public function __construct(
        ManifestValidatorInterface $manifestValidator,
        TaskYamlReader $taskYamlReader,
        TaskStorage $taskStorage
    ) {
        $this->manifestValidator = $manifestValidator;
        $this->taskYamlReader = $taskYamlReader;
        $this->taskStorage = $taskStorage;
        $this->collectionDto = new ManifestCollectionDto();
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\ManifestCollectionDto
     */
    public function collectAll(): ManifestCollectionDto
    {
        if (!$this->collectionDto->isEmpty()) {
            return $this->collectionDto;
        }

        $this->collectionDto = $this->taskYamlReader->readFiles();
        $this->taskStorage->setArrTasksCollection($this->collectionDto);
        $this->validate();

        return $this->collectionDto;
    }

    /**
     * @return void
     */
    protected function validate(): void
    {
        $this->collectionDto->setTasks(
            $this->manifestValidator->validate(
                TaskManifestConfiguration::NAME,
                $this->collectionDto->getTasks(),
            ),
        );

        $this->collectionDto->setTaskSets(
            $this->manifestValidator->validate(
                TaskSetManifestConfiguration::NAME,
                $this->collectionDto->getTaskSets(),
            ),
        );
    }
}
