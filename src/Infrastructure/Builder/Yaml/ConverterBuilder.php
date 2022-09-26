<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYaml;
use SprykerSdk\Sdk\Core\Domain\Entity\Converter;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;

class ConverterBuilder
{
    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYaml $taskYaml
     *
     * @return \SprykerSdk\SdkContracts\Entity\ConverterInterface|null
     */
    public function buildConverter(TaskYaml $taskYaml): ?ConverterInterface
    {
        $data = $taskYaml->getTaskData();
        if (!isset($data['report_converter'])) {
            return null;
        }

        return new Converter(
            $data['report_converter']['name'],
            $data['report_converter']['configuration'],
        );
    }
}
