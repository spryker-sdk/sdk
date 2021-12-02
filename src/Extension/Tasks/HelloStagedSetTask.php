<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Tasks;

use SprykerSdk\Sdk\Contracts\Entity\StagedTaskInterface;
use SprykerSdk\Sdk\Contracts\Entity\TaggedTaskInterface;
use SprykerSdk\Sdk\Contracts\Entity\TaskSetInterface;

class HelloStagedSetTask implements TaskSetInterface
{
    /**
     * @return string
     */
    public function getId(): string
    {
        return 'hello:php:staged_set';
    }

    /**
     * @return string
     */
    public function getShortDescription(): string
    {
        return 'will greet stages';
    }

    /**
     * @return array
     */
    public function getCommands(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getPlaceholders(): array
    {
        return [];
    }

    /**
     * @return string|null
     */
    public function getHelp(): ?string
    {
        return null;
    }

    /**
     * @param array<string> $tags
     *
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface>
     */
    public function getTasks(array $tags = []): array
    {
        $tasks = [
            new class implements TaggedTaskInterface, StagedTaskInterface {
                /**
                 * @return string
                 */
                public function getStage(): string
                {
                    return 'stageA';
                }

                /**
                 * @return array<string>
                 */
                public function getTags(): array
                {
                    return ['tagA'];
                }

                /**
                 * @return bool
                 */
                public function hasStopOnError(): bool
                {
                    return true;
                }

                /**
                 * @return string
                 */
                public function getId(): string
                {
                    return 'hello:php:stage_a';
                }

                /**
                 * @return string
                 */
                public function getShortDescription(): string
                {
                    return '';
                }

                /**
                 * @return array<\SprykerSdk\Sdk\Contracts\Entity\CommandInterface>
                 */
                public function getCommands(): array
                {
                    return [new GreeterCommand('Hello Stage A')];
                }

                /**
                 * @return array<\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface>
                 */
                public function getPlaceholders(): array
                {
                    return [];
                }

                /**
                 * @return string|null
                 */
                public function getHelp(): ?string
                {
                    return null;
                }
            },
            new class implements TaggedTaskInterface, StagedTaskInterface {
                /**
                 * @return string
                 */
                public function getStage(): string
                {
                    return 'stageB';
                }

                /**
                 * @return array<string>
                 */
                public function getTags(): array
                {
                    return ['tagB'];
                }

                /**
                 * @return bool
                 */
                public function hasStopOnError(): bool
                {
                    return true;
                }

                /**
                 * @return string
                 */
                public function getId(): string
                {
                    return 'hello:php:stage_b';
                }

                /**
                 * @return string
                 */
                public function getShortDescription(): string
                {
                    return '';
                }

                /**
                 * @return array<\SprykerSdk\Sdk\Contracts\Entity\CommandInterface>
                 */
                public function getCommands(): array
                {
                    return [new GreeterCommand('Hello Stage B')];
                }

                /**
                 * @return array<\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface>
                 */
                public function getPlaceholders(): array
                {
                    return [];
                }

                /**
                 * @return string|null
                 */
                public function getHelp(): ?string
                {
                    return null;
                }
            },
        ];

        if (empty($tags)) {
            return $tasks;
        }

        return array_filter($tasks, function (TaggedTaskInterface $task) use ($tags): bool {
            return count(array_intersect($task->getTags(), $tags)) > 0;
        });
    }
}
