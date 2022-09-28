<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\TaskYamlBuilder\TaskPartBuilder;

use SprykerSdk\Sdk\Core\Domain\Entity\Converter;
use SprykerSdk\Sdk\Core\Domain\Enum\TaskType;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlResultDto;
use SprykerSdk\Sdk\Infrastructure\Entity\Command;
use SprykerSdk\Sdk\Infrastructure\Storage\InMemoryTaskStorage;
use SprykerSdk\Sdk\Infrastructure\Validator\ConverterInputDataValidator;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;

class CommandPartBuilder implements TaskPartBuilderInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Validator\ConverterInputDataValidator
     */
    protected ConverterInputDataValidator $converterInputDataValidator;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Storage\InMemoryTaskStorage
     */
    protected InMemoryTaskStorage $storage;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Validator\ConverterInputDataValidator $converterInputDataValidator
     * @param \SprykerSdk\Sdk\Infrastructure\Storage\InMemoryTaskStorage $storage
     */
    public function __construct(ConverterInputDataValidator $converterInputDataValidator, InMemoryTaskStorage $storage)
    {
        $this->converterInputDataValidator = $converterInputDataValidator;
        $this->storage = $storage;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto $criteriaDto
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlResultDto $resultDto
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlResultDto
     */
    public function addPart(
        TaskYamlCriteriaDto $criteriaDto,
        TaskYamlResultDto $resultDto
    ): TaskYamlResultDto {
        $applicableTaskTypes = [
            TaskType::TASK_TYPE__LOCAL_CLI,
            TaskType::TASK_TYPE__LOCAL_CLI_INTERACTIVE,
        ];

        if (!in_array($criteriaDto->getType(), $applicableTaskTypes, true)) {
            return $resultDto;
        }

        $converter = $this->createConverter($criteriaDto->getTaskData());

        $resultDto->addCommand($this->createCommand($criteriaDto->getTaskData(), $converter));

        return $resultDto;
    }

    /**
     * @param array $inputData
     *
     * @return \SprykerSdk\SdkContracts\Entity\ConverterInterface|null
     */
    protected function createConverter(array $inputData): ?ConverterInterface
    {
        if (!$this->converterInputDataValidator->isValid($inputData)) {
            return null;
        }

        return new Converter(
            $inputData['report_converter']['name'],
            $inputData['report_converter']['configuration'],
        );
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
