<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\TaskYamlBuilder;

use SprykerSdk\Sdk\Core\Application\Exception\InvalidTaskTypeException;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class CompositeTaskBuilder implements TaskBuilderInterface
{
    /**
     * @var iterable<\SprykerSdk\Sdk\Infrastructure\Builder\TaskYamlBuilder\ApplicableTaskBuilderInterface>
     */
    protected iterable $typedTaskBuilders;

    /**
     * @param iterable<\SprykerSdk\Sdk\Infrastructure\Builder\TaskYamlBuilder\ApplicableTaskBuilderInterface> $typedTaskBuilders
     */
    public function __construct(iterable $typedTaskBuilders)
    {
        $this->typedTaskBuilders = $typedTaskBuilders;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto $taskYamlCriteriaDto
     *
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\InvalidTaskTypeException
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    public function build(TaskYamlCriteriaDto $taskYamlCriteriaDto): TaskInterface
    {
        foreach ($this->typedTaskBuilders as $taskBuilder) {
            if (!$taskBuilder->isApplicable($taskYamlCriteriaDto)) {
                continue;
            }

            return $taskBuilder->build($taskYamlCriteriaDto);
        }

        throw new InvalidTaskTypeException($taskYamlCriteriaDto->getType());
    }
}
