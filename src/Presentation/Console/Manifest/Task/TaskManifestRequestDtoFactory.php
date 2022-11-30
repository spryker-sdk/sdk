<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Manifest\Task;

use InvalidArgumentException;
use SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestFile;
use SprykerSdk\Sdk\Core\Application\Dto\Manifest\TaskManifestPlaceholderDto;
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
        $this->checkRequiredInput($receivedValues, $this->getTaskRequiredKeys());

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
            isset($receivedValues[TaskInteractionMap::PLACEHOLDERS_KEY])
                ? array_map([$this, 'createPlaceholderDto'], $receivedValues[TaskInteractionMap::PLACEHOLDERS_KEY])
                : [],
        );
    }

    /**
     * @param array $placeholderData
     *
     * @return \SprykerSdk\Sdk\Core\Application\Dto\Manifest\TaskManifestPlaceholderDto
     */
    protected function createPlaceholderDto(array $placeholderData): TaskManifestPlaceholderDto
    {
        $this->checkRequiredInput($placeholderData, $this->getPlaceholderRequiredKeys());

        return new TaskManifestPlaceholderDto(
            sprintf('%%%s%%', trim($placeholderData[TaskInteractionMap::PLACEHOLDER_NAME_KEY], '%')),
            $placeholderData[TaskInteractionMap::PLACEHOLDER_VALUE_RESOLVER_KEY],
            $placeholderData[TaskInteractionMap::PLACEHOLDER_OPTIONAL_KEY],
            $placeholderData[TaskInteractionMap::PLACEHOLDER_CONFIGURATION_KEY] ?? [],
        );
    }

    /**
     * @param array<string, mixed> $receivedValues
     * @param array<string> $requiredKeys
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    protected function checkRequiredInput(array $receivedValues, array $requiredKeys): void
    {
        foreach ($requiredKeys as $requiredKey) {
            if (!array_key_exists($requiredKey, $receivedValues)) {
                throw new InvalidArgumentException(sprintf('Value for `%s` is required', $requiredKey));
            }
        }
    }

    /**
     * @return array<string>
     */
    protected function getTaskRequiredKeys(): array
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

    /**
     * @return array<string>
     */
    protected function getPlaceholderRequiredKeys(): array
    {
        return [
            TaskInteractionMap::PLACEHOLDER_NAME_KEY,
            TaskInteractionMap::PLACEHOLDER_VALUE_RESOLVER_KEY,
            TaskInteractionMap::PLACEHOLDER_OPTIONAL_KEY,
        ];
    }
}
