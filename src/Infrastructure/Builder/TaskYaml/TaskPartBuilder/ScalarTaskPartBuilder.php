<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskPartBuilder;

use SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlCriteriaDto;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlResultDto;
use SprykerSdk\Sdk\Infrastructure\Exception\MissedTaskRequiredParamException;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

class ScalarTaskPartBuilder implements TaskPartBuilderInterface
{
    /**
     * @var array<string, string|bool|null|array>
     */
    public const OPTIONAL_KEY_TO_DEFAULT_VALUE_MAP = [
        'help' => null,
        'successor' => null,
        'deprecated' => false,
        'stage' => ContextInterface::DEFAULT_STAGE,
        'optional' => true,
        'stages' => [],
    ];

    /**
     * @var array<string>
     */
    protected const REQUIRED_KEYS = [
        'id',
        'short_description',
        'version',
    ];

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
        $resultDto = $this->addRequiredParts($criteriaDto, $resultDto);

        return $this->addOptionalParts($criteriaDto, $resultDto);
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlCriteriaDto $criteriaDto
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlResultDto $resultDto
     *
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\MissedTaskRequiredParamException
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlResultDto
     */
    protected function addRequiredParts(
        TaskYamlCriteriaDto $criteriaDto,
        TaskYamlResultDto $resultDto
    ): TaskYamlResultDto {
        $taskData = $criteriaDto->getTaskData();
        if (!$taskData) {
            return $resultDto;
        }

        foreach (static::REQUIRED_KEYS as $key) {
            if (!array_key_exists($key, $taskData)) {
                throw new MissedTaskRequiredParamException($key, $taskData['id'] ?? '');
            }

            $resultDto->addScalarPart($key, $taskData[$key]);
        }

        return $resultDto;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlCriteriaDto $criteriaDto
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlResultDto $resultDto
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlResultDto
     */
    protected function addOptionalParts(
        TaskYamlCriteriaDto $criteriaDto,
        TaskYamlResultDto $resultDto
    ): TaskYamlResultDto {
        $taskData = $criteriaDto->getTaskData();
        foreach (static::OPTIONAL_KEY_TO_DEFAULT_VALUE_MAP as $key => $defaultValue) {
            $resultDto->addScalarPart($key, $taskData[$key] ?? $defaultValue);
        }

        return $resultDto;
    }
}
