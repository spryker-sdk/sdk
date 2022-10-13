<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Collector;

use SprykerSdk\Sdk\Core\Application\Dependency\ManifestValidatorInterface;
use SprykerSdk\Sdk\Infrastructure\Dto\ManifestCollectionDto;
use SprykerSdk\Sdk\Infrastructure\ManifestValidator\TaskManifestConfiguration;
use SprykerSdk\Sdk\Infrastructure\ManifestValidator\TaskSetManifestConfiguration;
use SprykerSdk\Sdk\Infrastructure\Reader\TaskYamlReader;

class TaskYamlCollector
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
     * @var \SprykerSdk\Sdk\Infrastructure\Dto\ManifestCollectionDto
     */
    protected ManifestCollectionDto $collectionDto;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ManifestValidatorInterface $manifestValidator
     * @param \SprykerSdk\Sdk\Infrastructure\Reader\TaskYamlReader $taskYamlReader
     */
    public function __construct(ManifestValidatorInterface $manifestValidator, TaskYamlReader $taskYamlReader)
    {
        $this->manifestValidator = $manifestValidator;
        $this->taskYamlReader = $taskYamlReader;
        $this->collectionDto = new ManifestCollectionDto();
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\ManifestCollectionDto
     */
    public function collectAll(): ManifestCollectionDto
    {
        if (count($this->collectionDto->getTasks()) > 0 && count($this->collectionDto->getTaskSets()) > 0) {
            return $this->collectionDto;
        }

        $this->validate($this->taskYamlReader->readFiles());

        return $this->collectionDto;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\ManifestCollectionDto $collectionDto
     *
     * @return void
     */
    protected function validate(ManifestCollectionDto $collectionDto): void
    {
        $this->collectionDto->setTasks(
            $this->manifestValidator->validate(
                TaskManifestConfiguration::NAME,
                $collectionDto->getTasks(),
            ),
        );

        $this->collectionDto->setTaskSets(
            $this->manifestValidator->validate(
                TaskSetManifestConfiguration::NAME,
                $collectionDto->getTaskSets(),
            ),
        );
    }
}
