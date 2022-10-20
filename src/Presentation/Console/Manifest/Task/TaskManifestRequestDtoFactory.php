<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Manifest\Task;

use InvalidArgumentException;
use SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestFile;
use SprykerSdk\Sdk\Core\Application\Dto\Manifest\TaskManifestRequestDto;

class TaskManifestRequestDtoFactory
{
    /**
     * @param array<string, mixed> $receivedValues
     *
     * @return \SprykerSdk\Sdk\Core\Application\Dto\Manifest\TaskManifestRequestDto
     */
    public function createFromReceivedValues(array $receivedValues): TaskManifestRequestDto
    {
        $this->checkRequiredInput($receivedValues);

        return new TaskManifestRequestDto(
            $receivedValues[TaskInteractionMap::ID_KEY],
            $receivedValues[TaskInteractionMap::SHORT_DESCRIPTION_KEY],
            $receivedValues[TaskInteractionMap::VERSION_KEY],
            $receivedValues[TaskInteractionMap::TYPE_KEY],
            $receivedValues[TaskInteractionMap::COMMAND_KEY],
            new ManifestFile(
                $receivedValues[TaskInteractionMap::FILE_FORMAT_KEY],
                $receivedValues[TaskInteractionMap::FILE_NAME_KEY],
            ),
        );
    }

    /**
     * @param array<string, mixed> $receivedValues
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    protected function checkRequiredInput(array $receivedValues): void
    {
        foreach ($this->getRequiredKeys() as $requiredKey) {
            if (!array_key_exists($requiredKey, $receivedValues)) {
                throw new InvalidArgumentException(sprintf('Value for `%s` is required', $requiredKey));
            }
        }
    }

    /**
     * @return array<string>
     */
    protected function getRequiredKeys(): array
    {
        return [
            TaskInteractionMap::FILE_FORMAT_KEY,
            TaskInteractionMap::FILE_NAME_KEY,
            TaskInteractionMap::ID_KEY,
            TaskInteractionMap::SHORT_DESCRIPTION_KEY,
            TaskInteractionMap::COMMAND_KEY,
            TaskInteractionMap::VERSION_KEY,
            TaskInteractionMap::TYPE_KEY,
        ];
    }
}
