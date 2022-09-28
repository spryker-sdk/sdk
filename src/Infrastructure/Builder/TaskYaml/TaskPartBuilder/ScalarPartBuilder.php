<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskPartBuilder;

use SprykerSdk\Sdk\Core\Application\Exception\MissedTaskRequiredParamException;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlResultDto;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

class ScalarPartBuilder implements TaskPartBuilderInterface
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
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto $criteriaDto
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlResultDto $resultDto
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlResultDto
     *
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\MissedTaskRequiredParamException
     */
    public function addPart(
        TaskYamlCriteriaDto $criteriaDto,
        TaskYamlResultDto $resultDto
    ): TaskYamlResultDto {
        $resultDto = $this->addRequiredParts($criteriaDto, $resultDto);

        return $this->addOptionalParts($criteriaDto, $resultDto);
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto $criteriaDto
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlResultDto $resultDto
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlResultDto
     *
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\MissedTaskRequiredParamException
     */
    protected function addRequiredParts(
        TaskYamlCriteriaDto $criteriaDto,
        TaskYamlResultDto $resultDto
    ): TaskYamlResultDto {
        $taskData = $criteriaDto->getTaskData();
        foreach (static::REQUIRED_KEYS as $key) {
            if (!array_key_exists($key, $taskData)) {
                throw new MissedTaskRequiredParamException($key, $taskData['id'] ?? '');
            }

            $resultDto->addScalarPart($key, $taskData[$key]);
        }

        return $resultDto;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto $criteriaDto
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlResultDto $resultDto
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlResultDto
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
