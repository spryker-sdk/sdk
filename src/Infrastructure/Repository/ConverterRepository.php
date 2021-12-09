<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use SprykerSdk\Sdk\Contracts\Entity\CommandInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ConverterRepositoryInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Command;
use SprykerSdk\Sdk\Core\Domain\Entity\Converter;

class ConverterRepository implements ConverterRepositoryInterface
{
    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\CommandInterface $command
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Converter|null
     */
    public function getConverter(CommandInterface $command): ?Converter
    {
        if (!$command instanceof Command) {
            return null;
        }

        return new Converter('CheckstyleViolationReportConverter', ['input_file' => 'phpcs.codestyle.xml', 'producer' => 'phpcs']);

        return $this->findBy([
            'commandId' => $command->getId(),
        ]);
    }
}
