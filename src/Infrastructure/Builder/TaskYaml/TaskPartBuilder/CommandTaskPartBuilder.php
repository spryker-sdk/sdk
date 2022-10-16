<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskPartBuilder;

use SprykerSdk\Sdk\Core\Domain\Entity\Converter;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlCriteriaDto;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlResultDto;
use SprykerSdk\Sdk\Infrastructure\Entity\Command;
use SprykerSdk\Sdk\Infrastructure\Storage\TaskStorage;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Enum\Task;

class CommandTaskPartBuilder implements TaskPartBuilderInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Storage\TaskStorage
     */
    protected TaskStorage $storage;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Storage\TaskStorage $storage
     */
    public function __construct(TaskStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlCriteriaDto $criteriaDto
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlResultDto $resultDto
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlResultDto
     */
    public function addPart(
        TaskYamlCriteriaDto $criteriaDto,
        TaskYamlResultDto $resultDto
    ): TaskYamlResultDto {
        if (!$this->isApplicable($criteriaDto)) {
            return $resultDto;
        }

        $converter = null;
        if ($this->isConverterInputDataValid($criteriaDto->getTaskData())) {
            $converter = $this->createConverter($criteriaDto->getTaskData());
        }

        $resultDto->addCommand($this->createCommand($criteriaDto->getTaskData(), $converter));

        return $resultDto;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlCriteriaDto $criteriaDto
     *
     * @return bool
     */
    protected function isApplicable(TaskYamlCriteriaDto $criteriaDto): bool
    {
        $applicableTaskTypes = [
            Task::TYPE_LOCAL_CLI,
            Task::TYPE_LOCAL_CLI_INTERACTIVE,
        ];

        return in_array($criteriaDto->getType(), $applicableTaskTypes, true)
            && $criteriaDto->getTaskData();
    }

    /**
     * @param array $inputData
     *
     * @return \SprykerSdk\SdkContracts\Entity\ConverterInterface|null
     */
    protected function createConverter(array $inputData): ?ConverterInterface
    {
        return new Converter(
            $inputData['report_converter']['name'],
            $inputData['report_converter']['configuration'],
        );
    }

    /**
     * @param array $inputData
     *
     * @return bool
     */
    protected function isConverterInputDataValid(array $inputData): bool
    {
        return isset($inputData['report_converter']['name'])
            && isset($inputData['report_converter']['configuration']);
    }

    /**
     * @param array $inputData
     * @param \SprykerSdk\SdkContracts\Entity\ConverterInterface|null $converter
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Command
     */
    protected function createCommand(array $inputData, ?ConverterInterface $converter = null): Command
    {
        return new Command(
            $inputData['command'],
            $inputData['type'],
            false,
            $inputData['tags'] ?? [],
            $converter,
            $inputData['stage'] ?? ContextInterface::DEFAULT_STAGE,
            $inputData['error_message'] ?? '',
        );
    }
}
