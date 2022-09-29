<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\TaskYamlBuilder\TaskPartBuilder;

use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlResultDto;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;

class PlaceholderBuilderPart implements TaskPartBuilderInterface
{
    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto $criteriaDto
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlResultDto $resultDto
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlResultDto
     */
    public function addPart(TaskYamlCriteriaDto $criteriaDto, TaskYamlResultDto $resultDto): TaskYamlResultDto
    {
        $taskPlaceholders = $criteriaDto->getTaskData()['placeholders'] ?? [];

        foreach ($taskPlaceholders as $taskPlaceholder) {
            if ($taskPlaceholder instanceof PlaceholderInterface) {
                $resultDto->addPlaceholder($taskPlaceholder);

                continue;
            }

            if (is_array($taskPlaceholder)) {
                $placeholder = $this->createPlaceholder($taskPlaceholder);
                $resultDto->addPlaceholder($placeholder);
            }
        }

        return $resultDto;
    }

    /**
     * @param array $placeholderData
     *
     * @return \SprykerSdk\SdkContracts\Entity\PlaceholderInterface
     */
    protected function createPlaceholder(array $placeholderData): PlaceholderInterface
    {
        return new Placeholder(
            $placeholderData['name'],
            $placeholderData['value_resolver'],
            $placeholderData['configuration'] ?? [],
            $placeholderData['optional'] ?? false,
        );
    }
}
